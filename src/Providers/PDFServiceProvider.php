<?php

namespace Mpdf\Providers;

use Config;
use Illuminate\Support\ServiceProvider;
use Mpdf\Mpdf;

class PDFServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = dirname(__FILE__, 1) . '/config/pdf.php';
        $this->publishes([
            $configPath=>config_path('pdf.php')
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind('pdf', function ($cfg) {
            if (!empty($cfg)) {
                foreach ($cfg as $key => $value) {
                    Config::set('pdf.' . $key, $value);
                }
            }
            $config=Config::get('pdf');
            $mpdf = new Mpdf(
                $config['mode'],
                $config['format'],
                $config['defaultFontSize'],
                $config['defaultFont'],
                $config['marginLeft'],
                $config['marginRight'],
                $config['marginTop'],
                $config['marginBottom'],
                $config['marginHeader'],
                $config['marginFooter'],
                $config['orientation']
            );
            $permissions = [];
            $protection=$config['protection'];
            foreach ($protection['permissions'] as $perm => $enable) {
                if ($enable) {
                    $permissions[] = $perm;
                }
            }
            $mpdf->SetProtection(
                $permissions,
                $protection['user_password',
                $protection['owner_password',
                $protection['length']
            );
            $mpdf->SetTitle($config['title']);
            $mpdf->SetAuthor($config['author']);
            $mpdf->SetWatermarkText($config['watermark']);
            $mpdf->showWatermarkText = $config['showWatermark'];
            $mpdf->watermark_font = $config['watermarkFont'];
            $mpdf->watermarkTextAlpha = $config['watermarkTextAlpha'];
            $mpdf->SetDisplayMode($config['displayMode']);
            return new PDFWrapper($mpdf);
        });
    }
}
