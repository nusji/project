<?php
// app/Providers/DompdfServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Dompdf\Dompdf;
use Dompdf\Options;

class DompdfServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $options = new Options();
        $options->set('defaultFont', 'Sarabun'); // กำหนดฟอนต์เริ่มต้นเป็น Sarabun

        $dompdf = new Dompdf($options);

        // เพิ่มฟอนต์ Sarabun
        $dompdf->getOptions()->set('isFontSubsettingEnabled', true);
        $dompdf->setPaper('A4', 'portrait');
    }
}
