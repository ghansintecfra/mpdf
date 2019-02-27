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

        $this->app->bind('pdf', function ($app) {
            $cfg=$app['config']['pdf'];
            $mpdf = new Mpdf(
                $cfg
            );
            $permissions = [];
            foreach ($cfg['protection']['permissions'] as $perm => $enable) {
                if ($enable) {
                    $permissions[] = $perm;
                }
            }
            $mpdf->SetProtection(
                $permissions,
                $cfg['protection']['user_password'],
                $cfg['protection']['owner_password'],
                $cfg['protection']['length']
            );
            $mpdf->SetTitle($cfg['title']);
            $mpdf->SetAuthor($cfg['author']);
            $mpdf->SetWatermarkText($cfg['watermark']);
            $mpdf->showWatermarkText = $cfg['showWatermark'];
            $mpdf->watermark_font = $cfg['watermarkFont'];
            $mpdf->watermarkTextAlpha = $cfg['watermarkTextAlpha'];
            $mpdf->SetDisplayMode($cfg['displayMode']);
            return new PDFWrapper($mpdf);
        });
    }
}
