<?php


namespace App\Services;

use App\Models\CommercialRegisterLibrary;
use App\Models\ConcessionLibrary;
use App\Models\CustomerLibrary;
use App\Models\EgyptianFederationLibrary;
use App\Models\PartLibrary;
use App\Models\RegisterAddedValueLibrary;
use App\Models\SupplierLibrary;
use App\Models\TaxCardLibrary;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

trait LibraryServices
{
    public function libraryPath($user, $type)
    {

        $libraryPath = $user->library_path;

        if (!$libraryPath) {

            $libraryPath = Str::slug($user->id);

            if (in_array($type, ['part','customer','supplier'])) {

                $libraryPath = Str::slug($user->name_en . '-' . $user->id);
            }
            if ($type =='egyptian_federation') {

                $libraryPath = Str::slug($user->membership_no . '-' . $user->id);
            }
            if ($type =='commercial_register') {

                $libraryPath = Str::slug($user->commercial_registry_office . '-' . $user->id);
            }
            if ($type =='tax_card') {
                $libraryPath = Str::slug($user->activity . '-' . $user->id);
            }
            if ($type =='register_added_value'){
                 $libraryPath = Str::slug($user->area . '-' . $user->id);
            }
            $user->library_path = Str::slug($libraryPath);

            $user->save();
        }

        $dirPath = 'app/public/' . $type . '_library/';

        $path = storage_path($dirPath);

        if (!\File::exists($path)) {

            \File::makeDirectory($path, 0777, true, true);
        }

        $path = $path . $libraryPath;

        if (!\File::exists($path)) {

            \File::makeDirectory($path, 0777, true, true);
        }

        return $libraryPath;
    }

    public function uploadFiles($file, $directory)
    {
        $time = time();
        $random = rand(1, 100000);
        $divide = $time / $random;
        $encryption = md5($divide);
        $randomName = substr($encryption, 0, 20);

        $fileName = $randomName . '.' . $file->getClientOriginalExtension();

        $path = "public/{$directory}/";

        $file->storeAs($path, $fileName);

        $data = [];

        $data['file_name'] = $fileName;
        $data['extension'] = $file->getClientOriginalExtension();
        $data['name'] = $file->getClientOriginalName();

        return $data;
    }

    public function createEgyptianFederationLibrary($egyptian_federation_id, $file_name, $extension, $name,$title_ar,$title_en)
    {
        $fileInLibrary = EgyptianFederationLibrary::create([
            'egyptian_federation_id' => $egyptian_federation_id,
            'file_name' => $file_name,
            'extension' => $extension,
            'name'=> $name,
            'title_ar'=> $title_ar,
            'title_en'=> $title_en,
        ]);

        return $fileInLibrary;
    }
    public function createTaxCardLibrary($tax_card_id, $file_name, $extension, $name,$title_ar,$title_en)
    {
        $fileInLibrary = TaxCardLibrary::create([
            'tax_card_id' => $tax_card_id,
            'file_name' => $file_name,
            'extension' => $extension,
            'name'=> $name,
            'title_ar'=> $title_ar,
            'title_en'=> $title_en,
        ]);

        return $fileInLibrary;
    }
    public function createCommercialRegisterLibrary($commercial_register_id, $file_name, $extension, $name,$title_ar,$title_en)
    {
        $fileInLibrary = CommercialRegisterLibrary::create([
            'commercial_register_id' => $commercial_register_id,
            'file_name' => $file_name,
            'extension' => $extension,
            'name'=> $name,
            'title_ar'=> $title_ar,
            'title_en'=> $title_en,
        ]);

        return $fileInLibrary;
    }
    public function createRegisterAddedValueLibrary($register_added_value_id, $file_name, $extension, $name,$title_ar,$title_en)
    {
        $fileInLibrary = RegisterAddedValueLibrary::create([
            'register_added_value_id' => $register_added_value_id,
            'file_name' => $file_name,
            'extension' => $extension,
            'name'=> $name,
            'title_ar'=> $title_ar,
            'title_en'=> $title_en,
        ]);

        return $fileInLibrary;
    }
    public function createSupplierLibrary($supplier_id, $file_name, $extension, $name)
    {
        $fileInLibrary = SupplierLibrary::create([
            'supplier_id' => $supplier_id,
            'file_name' => $file_name,
            'extension' => $extension,
            'name'=> $name
        ]);

        return $fileInLibrary;
    }

    public function createCustomerLibrary($customer_id, $file_name, $extension, $name)
    {
        $fileInLibrary = CustomerLibrary::create([
            'customer_id' => $customer_id,
            'file_name' => $file_name,
            'extension' => $extension,
            'name' => $name,
        ]);

        return $fileInLibrary;
    }

    public function createPartLibrary($part_id, $file_name, $extension, $name)
    {
        $fileInLibrary = PartLibrary::create([
            'part_id' => $part_id,
            'file_name' => $file_name,
            'extension' => $extension,
            'name' => $name,
        ]);

        return $fileInLibrary;
    }

}
