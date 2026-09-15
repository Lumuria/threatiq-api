<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attack;
use App\Models\AttackTranslation;

class AttackSeeder extends Seeder
{
    public function run(): void
    {
        $attacks = [
            [
                'id' => 1,
                'name' => 'ILOVEYOU',
                'color' => 'bg-red-500',
                'translations' => [
                    'ar' => [
                        'title' => 'هجوم أحبك',
                        'date' => 'مايو 2000',
                        'type' => 'فيروس',
                        'target' => 'أجهزة المستخدمين الشخصية',
                        'damage' => 'أضرار بقيمة 10 مليارات دولار',
                        'description' => 'فيروس انتشر عبر البريد الإلكتروني مع موضوع ILOVEYOU وكان يحذف الملفات ويرسل نفسه.',
                        'prevention' => 'تجنب فتح المرفقات غير المعروفة واستخدم مضاد فيروسات',
                        'detection' => 'مراقبة البريد الإلكتروني وبرامج الحماية',
                        'solution' => 'إزالة الفيروس واستعادة النسخ الاحتياطية',
                        'severity' => 'عالية',
                    ],
                    'en' => [
                        'title' => 'ILOVEYOU Attack',
                        'date' => 'May 2000',
                        'type' => 'Virus',
                        'target' => 'Personal computers',
                        'damage' => 'Damage worth $10 billion',
                        'description' => 'A virus spread via email with subject ILOVEYOU, deleting files and spreading itself.',
                        'prevention' => 'Avoid unknown attachments and use antivirus software',
                        'detection' => 'Email monitoring and antivirus systems',
                        'solution' => 'Remove virus and restore backups',
                        'severity' => 'High',
                    ],
                ],
            ],

            [
                'id' => 2,
                'name' => 'WannaCry',
                'color' => 'bg-red-600',
                'translations' => [
                    'ar' => [
                        'title' => 'وانا كراي',
                        'date' => 'مايو 2017',
                        'type' => 'برمجية خبيثة',
                        'target' => 'أنظمة ويندوز',
                        'damage' => 'تأثر أكثر من 300,000 جهاز',
                        'description' => 'هجوم عالمي قام بتشفير الملفات وطلب فدية لفك التشفير.',
                        'prevention' => 'تحديث النظام وعمل نسخ احتياطية',
                        'detection' => 'مراقبة الشبكة وكشف التسلل',
                        'solution' => 'عزل الأجهزة واستعادة البيانات',
                        'severity' => 'عالية جداً',
                    ],
                    'en' => [
                        'title' => 'WannaCry',
                        'date' => 'May 2017',
                        'type' => 'Malware',
                        'target' => 'Windows systems',
                        'damage' => 'Over 300,000 devices affected',
                        'description' => 'A global ransomware attack that encrypted files and demanded payment.',
                        'prevention' => 'Update systems and create backups',
                        'detection' => 'Network monitoring and IDS systems',
                        'solution' => 'Isolate devices and restore data',
                        'severity' => 'Critical',
                    ],
                ],
            ],

            [
                'id' => 3,
                'name' => 'Stuxnet',
                'color' => 'bg-yellow-500',
                'translations' => [
                    'ar' => [
                        'title' => 'ستوكسنت',
                        'date' => '2010',
                        'type' => 'دودة كمبيوتر',
                        'target' => 'الأنظمة الصناعية',
                        'damage' => 'تدمير أجهزة نووية',
                        'description' => 'دودة متطورة استهدفت أنظمة التحكم الصناعية.',
                        'prevention' => 'عزل الأنظمة عن الإنترنت',
                        'detection' => 'مراقبة الأنظمة الصناعية',
                        'solution' => 'تحديث الأنظمة وتعزيز الأمان',
                        'severity' => 'متوسطة',
                    ],
                    'en' => [
                        'title' => 'Stuxnet',
                        'date' => '2010',
                        'type' => 'Computer Worm',
                        'target' => 'Industrial systems',
                        'damage' => 'Damage to nuclear equipment',
                        'description' => 'Advanced worm targeting industrial control systems.',
                        'prevention' => 'Isolate systems from internet',
                        'detection' => 'Monitor industrial systems',
                        'solution' => 'Update systems and improve security',
                        'severity' => 'Medium',
                    ],
                ],
            ],

            [
                'id' => 4,
                'name' => 'Equifax',
                'color' => 'bg-red-500',
                'translations' => [
                    'ar' => [
                        'title' => 'اختراق إكويفاكس',
                        'date' => '2017',
                        'type' => 'اختراق بيانات',
                        'target' => 'شركة إكويفاكس',
                        'damage' => 'تسريب بيانات 147 مليون شخص',
                        'description' => 'اختراق ضخم أدى إلى تسريب بيانات حساسة.',
                        'prevention' => 'تحديث الأنظمة وتشفير البيانات',
                        'detection' => 'مراقبة البيانات وكشف التسلل',
                        'solution' => 'تعزيز الأمان وإشعار المستخدمين',
                        'severity' => 'عالية',
                    ],
                    'en' => [
                        'title' => 'Equifax Breach',
                        'date' => '2017',
                        'type' => 'Data Breach',
                        'target' => 'Equifax company',
                        'damage' => 'Data leak of 147 million people',
                        'description' => 'Massive breach exposing sensitive user data.',
                        'prevention' => 'Update systems and encrypt data',
                        'detection' => 'Data monitoring and intrusion detection',
                        'solution' => 'Improve security and notify users',
                        'severity' => 'High',
                    ],
                ],
            ],

            [
                'id' => 5,
                'name' => 'SolarWinds',
                'color' => 'bg-red-600',
                'translations' => [
                    'ar' => [
                        'title' => 'سولار ويندز',
                        'date' => '2020',
                        'type' => 'هجوم سلسلة التوريد',
                        'target' => 'شركات وحكومات',
                        'damage' => 'تأثر آلاف المؤسسات',
                        'description' => 'هجوم عبر تحديث برمجي مصاب أدى لاختراق واسع.',
                        'prevention' => 'مراقبة التحديثات وتطبيق الثقة الصفرية',
                        'detection' => 'تحليل السلوك ومراقبة الشبكة',
                        'solution' => 'إزالة البرامج المصابة وتعزيز الأمان',
                        'severity' => 'عالية جداً',
                    ],
                    'en' => [
                        'title' => 'SolarWinds Attack',
                        'date' => '2020',
                        'type' => 'Supply Chain Attack',
                        'target' => 'Companies and governments',
                        'damage' => 'Thousands of organizations affected',
                        'description' => 'Attack via infected software update affecting many organizations.',
                        'prevention' => 'Monitor updates and apply zero trust',
                        'detection' => 'Behavior analysis and network monitoring',
                        'solution' => 'Remove infected software and improve security',
                        'severity' => 'Critical',
                    ],
                ],
            ],
        ];

        foreach ($attacks as $attackData) {
            $attack = Attack::updateOrCreate(
                ['id' => $attackData['id']],
                [
                    'name' => $attackData['name'],
                    'color' => $attackData['color'],
                ]
            );

            foreach ($attackData['translations'] as $language => $translation) {
                AttackTranslation::updateOrCreate(
                    [
                        'attack_id' => $attack->id,
                        'language' => $language,
                    ],
                    $translation
                );
            }
        }
    }
}