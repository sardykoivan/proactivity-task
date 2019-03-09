<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 08.03.2019
 * Time: 9:26
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;

use PhpQuery\PhpQuery;
use function PhpQuery\pq as pq;
use Illuminate\Support\Facades\DB;



class Tenders extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "tenders:parse";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Delete all posts";


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//        $tenders_url = "https://www.sberbank.ru/ru/fpartners/purchase/notification";
        $tenders_url = "/var/www/proactivity-task/example.html";
        $base_url = "https://www.sberbank.ru/ru/fpartners/purchase/notification";

        PhpQuery::newDocument(file_get_contents($tenders_url));

        $data_upload = [];

        foreach (pq('div.competitions-results__item') as $key => $block) {

            // название тендера
            $name = trim(pq($block)->find('div.competitions-results__item-desc')->text());
            $data_upload[$key]['name'] = $name;

            // даты
            $dates = pq($block)->find('div.competitions-results__item-dates > div.competitions-results__item-date');
            foreach ($dates as $date) {

                // Дата публикации конкурса
                if (pq($date)->find('span.competitions-results__date-label')->text() == "Дата публикации конкурса: ") {
                    $date_text = trim(str_replace("Дата публикации конкурса: ", "", pq($date)->text()));
                    $date_publication = \DateTime::createFromFormat('d.m.y', $date_text);
                    $data_upload[$key]['date_publication'] = $date_publication;
                }

                // Дата окончания подачи заявок:
                if (pq($date)->find('span.competitions-results__date-label')->text() == "Дата окончания подачи заявок: ") {
                    $date_text = trim(str_replace("Дата окончания подачи заявок: ", "", pq($date)->text()));
                    $date_expire = \DateTime::createFromFormat('d.m.y', $date_text);
                    $data_upload[$key]['date_expire'] = $date_expire;
                }

                // Дата подведения итогов:
                if (pq($date)->find('span.competitions-results__date-label')->text() == "Дата подведения итогов: ") {
                    $date_text = trim(str_replace("Дата подведения итогов: ", "", pq($date)->text()));
                    $date_totals = \DateTime::createFromFormat('d.m.y', $date_text);
                    $data_upload[$key]['date_totals'] = $date_totals;
                }
            }

            // Ссылка на владельца тендера
//            $query_string_details = pq($block)->find('a.competitions-results__item-link')->attr('href');
            $link_details = pq($block)->find('a.competitions-results__item-link')->attr('href');
//            $link_details = $base_url . $query_string_details;
            $data_upload[$key]['link_details'] = $link_details;
        }

        return true;
    }
}
