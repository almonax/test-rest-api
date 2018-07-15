<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use GuzzleHttp\Client;

class UsersTableSeeder extends Seeder
{
    const RANDOM_USER_API_URL = 'https://randomuser.me/api/';

    /** @var \App\Classes\ImageService $imageService */
    private $imageService;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        echo 'Seeder ' . self::class . " is running\n\r";
        $seederLimit = 45;
        $this->imageService = new \App\Classes\ImageService();
        $faker = Faker\Factory::create();
        try {
            DB::beginTransaction();
            for ($i = 0; $i < $seederLimit; $i++) {
                $user = User::create([
                    'name'      => $faker->name(),
                    'email'     => $faker->email(),
                    'phone'     => '7' . $faker->numberBetween(1000000000, 9999999999),
                    'password'  => Hash::make('qwer1234')
                ]);
                $user->update([
                    'avatar'    => $this->getUserImage($user),
                ]);
                echo "User with ID {$user->id} saved\n";
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error($e->getMessage());
        }
        echo 'Seeder ' . self::class . " execution completed\n\r";
    }

    /**
     * @param User $user
     * @return string
     * @throws Exception
     */
    private function getUserImage($user)
    {
        $imageExtensions = [
            'image/jpeg' => '.jpg',
            'image/png'  => '.png',
            'image/gif'  => '.gif'
        ];
        $profilePicture = null;

        try {
            $client = new Client();
            $response = $client->get(self::RANDOM_USER_API_URL);
            $profilePicture = json_decode($response->getBody()->getContents());
            $profilePicture = current($profilePicture->results)->picture->large;
        } catch (\Exception $e) {
            echo __METHOD__ . " API request error\n\r";
            throw new \Exception($e->getMessage());
        }
        try {
            $imageManager = new ImageManager();
            $imageResource = $imageManager->make($profilePicture);
            $imageName = sha1(random_bytes(20)) . $imageExtensions[$imageResource->mime()];
            $imagePath = public_path($user->getAvatarPath());
            if (!file_exists($imagePath)) {
                mkdir($imagePath, 755, true);
            }
            $imageResource->save($imagePath . $imageName, 100);
            $this->imageService->createThumb($imageName, $user->getAvatarPath());
            return $imageName;
        } catch (\Exception $e) {
            echo __METHOD__ . " Error on saving image\n\r";
            throw new \Exception($e->getMessage());
        }
    }
}
