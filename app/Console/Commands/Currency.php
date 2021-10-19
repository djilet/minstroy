<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Output\OutputInterface;

class Currency extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:update {--date= : Дата в формате дд/мм/гггг}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновление списка курса валют';

    protected $url = 'http://www.cbr.ru/scripts/XML_daily.asp?date_req=';
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date = $this->option('date');
        if ($date != null) {
            if (!preg_match('/^\d\d\/\d\d\/\d\d\d\d$/iu', $this->option('date'))) {
                $this->error('Не верный формат даты. Ожидаемый дд/мм/гггг');
                return false;
            }
        } else {
            $date = Carbon::now()->format('d/m/Y');
        }

        if($this->output->isVerbose()){
            $this->info('Скачиваю файл курса валют за ' . $date);
        }
        
        if ($cbrf = simplexml_load_file($this->url.$date)) {
            
            $configCurrency = config('meteor.modules.catalog.currency');
            $currencies = \App\Models\Currency::all()->keyBy('slug');

            if (in_array('RUB', $configCurrency)) {
                $currency = $currencies->get('RUB', new \App\Models\Currency);
                if (! $currency->exists) {
                    $currency->slug = 'RUB';
                    $currency->title = 'Рубль';
                    $currency->value = 1;
                    $currency->save();
                }
            }

            foreach ($cbrf->Valute as $valute) {
                if (in_array($valute->CharCode, $configCurrency)) {
                    $currency = $currencies->get(trim($valute->CharCode), new \App\Models\Currency);
                    if (! $currency->exists) {
                        $currency->slug = strtoupper(trim($valute->CharCode));
                        $currency->title = strtoupper(trim($valute->Name));
                    }
                    
                    $currency->value = floatval(str_replace(',', '.', $valute->Value));
                    $currency->save();

                    if($this->output->isVerbose()){
                        $this->info($currency->slug . ' = ' . $currency->value);
                    }
                }
            }
            
        }
        
        $this->info('Курс валют обновлен');
        
        return true;
    }
}
