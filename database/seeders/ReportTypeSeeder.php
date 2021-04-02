<?php

namespace Database\Seeders;

use App\Models\ReportType;
use Illuminate\Database\Seeder;

class ReportTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (ReportType::query()->count()) ReportType::query()->truncate();

        $this->userReportTypes();
        $this->postReportTypes();
    }

    private function userReportTypes()
    {
        $reports = [
            'low' => [
                'هرزنامه'
            ],
            'medium' => [
                'بد دهنی',
                'کپی برداری بدون اجازه',
                'نمایش خشونت',
            ],
            'high' => [
                'توهین به افراد',
                'اذیت و آزار',
                'قلدری و تهدید',
                'خرید و فروش یا مصرف دارو و مواد مخدر',
            ],
            'very high' => [
                'تصاویر مستهجن',
                'توهین به ادیان و مذاهب',
            ]
        ];

        foreach ($reports as $degree => $report) {
            foreach ($report as $value) {
                ReportType::query()->create([
                    'title' => $value,
                    'type' => 'user',
                    'importance' => $degree
                ]);
            }
        }
    }

    private function postReportTypes()
    {
        $reports = [
            'low' => [
                'هرزنامه'
            ],
            'medium' => [
                'کپی برداری بدون اجازه',
                'نمایش خشونت',
            ],
            'high' => [
                'توهین به افراد',
                'خرید و فروش یا مصرف دارو و مواد مخدر',
            ],
            'very high' => [
                'تصویر مستهجن',
                'توهین به ادیان و مذاهب',
            ]
        ];

        foreach ($reports as $degree => $report) {
            foreach ($report as $value) {
                ReportType::query()->create([
                    'title' => $value,
                    'type' => 'post',
                    'importance' => $degree
                ]);
            }
        }
    }
}
