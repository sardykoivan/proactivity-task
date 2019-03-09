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
    protected $description = "Parsing sberbank tenders";


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //  $tenders_url = "https://www.sberbank.ru/ru/fpartners/purchase/notification";
        $tenders_file = "/var/www/proactivity-task/examples/start_page.html";

        PhpQuery::newDocument(file_get_contents($tenders_file));

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
            $link_details = pq($block)->find('a.competitions-results__item-link')->attr('href');
            $data_upload[$key]['link_details'] = $link_details;
            $data_upload[$key]['owner_id'] = substr($link_details, -5, 5);
        }

        // hardcode id owners
        $detail_pages_ids = [47229, 47230, 47231];

        foreach ($detail_pages_ids as $detail_pages_id) {
            $file = sprintf("/var/www/proactivity-task/examples/%s.html", $detail_pages_id);
            PhpQuery::newDocument(file_get_contents($file));

            $data_upload2 = [];

            foreach (pq("div:regex(class, ^.purchase-details(.+)item$) .kit-row") as $block2) {

                foreach (pq($block2)->children() as $sub_block) {

                    $data_upload2['id'] = $detail_pages_id;

                    if (trim(pq($sub_block)->text()) == "Номер извещения") {
                        $data_upload2['notification_number'] = trim(pq($sub_block)->next()->text());
                    }
                    if (trim(pq($sub_block)->text()) == "Краткое наименование закупки") {
                        $data_upload2['short_title'] = trim(pq($sub_block)->next()->text());
                    }
                    if (trim(pq($sub_block)->text()) == "Дата публикации") {
                        $date_text = trim(pq($sub_block)->next()->text());
                        $date = \DateTime::createFromFormat('d.m.y', $date_text)->format('Y-m-d');
                        $data_upload2['date_publication'] = $date;
                    }
                    if (trim(pq($sub_block)->text()) == "Дата окончания подачи заявок") {
                        $date_text = trim(pq($sub_block)->next()->text());
                        $data_upload2['date_expire'] = \DateTime::createFromFormat('d.m.Y H:i', $date_text);
                    }
                    if (trim(pq($sub_block)->text()) == "Дата подведения итогов") {
                        $date_text = trim(pq($sub_block)->next()->text());
                        $data_upload2['date_totals'] = \DateTime::createFromFormat('d.m.Y H:i', $date_text);
                    }
                    if (trim(pq($sub_block)->text()) == "Наименование") {
                        $data_upload2['title'] = trim(pq($sub_block)->next()->text());
                    }
                    if (trim(pq($sub_block)->text()) == "Адрес") {
                        $data_upload2['address'] = trim(pq($sub_block)->next()->text());
                    }
                    if (trim(pq($sub_block)->text()) == "Телефон") {
                        $data_upload2['phone'] = trim(pq($sub_block)->next()->text());
                    }
                    if (trim(pq($sub_block)->text()) == "Email") {
                        $data_upload2['email'] = trim(pq($sub_block)->next()->text());
                    }
                    if (trim(pq($sub_block)->text()) == "Контактное лицо") {
                        $data_upload2['contact_person'] = trim(pq($sub_block)->next()->text());
                    }
                    if (trim(pq($sub_block)->text()) == "Предмет договора") {
                        $data_upload2['subject'] = trim(pq($sub_block)->next()->text());
                    }
                    if (trim(pq($sub_block)->text()) == "Место поставки товара, выполнения работ, оказания услуг") {
                        $data_upload2['supply_place'] = trim(pq($sub_block)->next()->text());
                    }
                    if (trim(pq($sub_block)->text()) == "Начальная(максимальная) цена контракта") {
                        $data_upload2['start_price'] = trim(pq($sub_block)->next()->text());
                    }
                    if (trim(pq($sub_block)->text()) == "Валюта контракта") {
                        $data_upload2['currency'] = trim(pq($sub_block)->next()->text());
                    }
                    if (trim(pq($sub_block)->text()) == "Дата рассмотрения заявок") {
                        $date_text = trim(pq($sub_block)->next()->text());
                        $data_upload2['date_consideration'] = \DateTime::createFromFormat('d.m.Y H:i', $date_text);
                    }
                    if (trim(pq($sub_block)->text()) == "Место рассмотрения заявок и подведения итогов") {
                        $data_upload2['consideration_place'] = trim(pq($sub_block)->next()->text());
                    }
                }
            }

            // owners save
            DB::table('owners')
                ->insert($data_upload2);

        }

        // tenders save
        foreach ($data_upload as $key => $data_row) {
            DB::table('tenders')
                ->insert($data_row);
        }

        return true;
    }
}
