<?php

namespace Database\Seeders;

use App\Models\Prevention;
use Illuminate\Database\Seeder;

class PreventionSeeder extends Seeder
{
    public function run(): void
    {
        $preventions = [
            [
                'category' => [
                    'ar' => 'أساسيات الأمان',
                    'en' => 'Security Basics',
                ],
                'importance' => [
                    'ar' => 'عالية',
                    'en' => 'High',
                ],
                'difficulty' => [
                    'ar' => 'سهل',
                    'en' => 'Easy',
                ],
                'title' => [
                    'ar' => 'كلمات المرور القوية',
                    'en' => 'Strong Passwords',
                ],
                'description' => [
                    'ar' => 'استخدام كلمات مرور قوية ومعقدة لحماية الحسابات',
                    'en' => 'Use strong and complex passwords to protect accounts',
                ],
                'tips' => [
                    'ar' => [
                        'استخدم كلمات مرور بطول 12 حرف على الأقل',
                        'امزج بين الأحرف الكبيرة والصغيرة والأرقام والرموز',
                        'تجنب استخدام معلومات شخصية',
                        'استخدم كلمة مختلفة لكل حساب',
                        'استخدم مدير كلمات المرور',
                    ],
                    'en' => [
                        'Use passwords with at least 12 characters',
                        'Mix uppercase, lowercase, numbers, and symbols',
                        'Avoid using personal information',
                        'Use different passwords for each account',
                        'Use a password manager',
                    ],
                ],
            ],
            [
                'category' => [
                    'ar' => 'صيانة النظام',
                    'en' => 'System Maintenance',
                ],
                'importance' => [
                    'ar' => 'عالية جداً',
                    'en' => 'Critical',
                ],
                'difficulty' => [
                    'ar' => 'سهل',
                    'en' => 'Easy',
                ],
                'title' => [
                    'ar' => 'التحديثات الأمنية',
                    'en' => 'Security Updates',
                ],
                'description' => [
                    'ar' => 'الحفاظ على تحديث البرمجيات وأنظمة التشغيل',
                    'en' => 'Keep software and systems updated',
                ],
                'tips' => [
                    'ar' => [
                        'فعّل التحديثات التلقائية',
                        'حدّث التطبيقات بانتظام',
                        'حدّث مضاد الفيروسات',
                        'راجع التحديثات شهرياً',
                        'احذف البرامج غير المستخدمة',
                    ],
                    'en' => [
                        'Enable automatic updates',
                        'Update apps regularly',
                        'Update antivirus software',
                        'Check updates monthly',
                        'Remove unused software',
                    ],
                ],
            ],
            [
                'category' => [
                    'ar' => 'حماية البيانات',
                    'en' => 'Data Protection',
                ],
                'importance' => [
                    'ar' => 'عالية',
                    'en' => 'High',
                ],
                'difficulty' => [
                    'ar' => 'متوسط',
                    'en' => 'Medium',
                ],
                'title' => [
                    'ar' => 'النسخ الاحتياطية',
                    'en' => 'Backups',
                ],
                'description' => [
                    'ar' => 'إنشاء نسخ احتياطية منتظمة للبيانات',
                    'en' => 'Create regular backups of important data',
                ],
                'tips' => [
                    'ar' => [
                        'اتبع قاعدة 3-2-1',
                        'اختبر النسخ الاحتياطية',
                        'استخدم التشفير',
                        'احفظ نسخة خارجية',
                        'جدول النسخ التلقائي',
                    ],
                    'en' => [
                        'Follow the 3-2-1 backup rule',
                        'Test backups regularly',
                        'Use encryption',
                        'Store offsite backup',
                        'Schedule automatic backups',
                    ],
                ],
            ],
            [
                'category' => [
                    'ar' => 'الاستخدام اليومي',
                    'en' => 'Daily Usage',
                ],
                'importance' => [
                    'ar' => 'عالية',
                    'en' => 'High',
                ],
                'difficulty' => [
                    'ar' => 'سهل',
                    'en' => 'Easy',
                ],
                'title' => [
                    'ar' => 'التصفح الآمن',
                    'en' => 'Safe Browsing',
                ],
                'description' => [
                    'ar' => 'ممارسات آمنة أثناء التصفح',
                    'en' => 'Safe practices while browsing the internet',
                ],
                'tips' => [
                    'ar' => [
                        'تحقق من المواقع',
                        'استخدم HTTPS',
                        'تجنب الروابط المشبوهة',
                        'استخدم متصفح آمن',
                        'فعّل مانع الإعلانات',
                    ],
                    'en' => [
                        'Verify websites',
                        'Use HTTPS websites',
                        'Avoid suspicious links',
                        'Use secure browser',
                        'Enable ad blocker',
                    ],
                ],
            ],
            [
                'category' => [
                    'ar' => 'الشبكات',
                    'en' => 'Networking',
                ],
                'importance' => [
                    'ar' => 'عالية',
                    'en' => 'High',
                ],
                'difficulty' => [
                    'ar' => 'متوسط',
                    'en' => 'Medium',
                ],
                'title' => [
                    'ar' => 'أمان الشبكة',
                    'en' => 'Network Security',
                ],
                'description' => [
                    'ar' => 'حماية الشبكات والاتصالات',
                    'en' => 'Secure networks and connections',
                ],
                'tips' => [
                    'ar' => [
                        'استخدم WPA3',
                        'غيّر كلمة الراوتر',
                        'فعّل الجدار الناري',
                        'تجنب الشبكات العامة',
                        'استخدم VPN',
                    ],
                    'en' => [
                        'Use WPA3 encryption',
                        'Change router password',
                        'Enable firewall',
                        'Avoid public networks',
                        'Use VPN when needed',
                    ],
                ],
            ],
            [
                'category' => [
                    'ar' => 'الهندسة الاجتماعية',
                    'en' => 'Social Engineering',
                ],
                'importance' => [
                    'ar' => 'عالية جداً',
                    'en' => 'Critical',
                ],
                'difficulty' => [
                    'ar' => 'متوسط',
                    'en' => 'Medium',
                ],
                'title' => [
                    'ar' => 'التوعية بالتصيد',
                    'en' => 'Phishing Awareness',
                ],
                'description' => [
                    'ar' => 'التعرف على هجمات التصيد',
                    'en' => 'Identify phishing attempts',
                ],
                'tips' => [
                    'ar' => [
                        'تحقق من المرسل',
                        'لا تنقر روابط مشبوهة',
                        'تحقق من الطلبات',
                        'احذر الرسائل العاجلة',
                        'تعلم علامات التصيد',
                    ],
                    'en' => [
                        'Verify sender',
                        'Avoid suspicious links',
                        'Verify requests',
                        'Beware urgent messages',
                        'Learn phishing signs',
                    ],
                ],
            ],
        ];

        foreach ($preventions as $data) {
            $prevention = Prevention::create([
                'category_ar' => $data['category']['ar'],
                'category_en' => $data['category']['en'],
                'importance_ar' => $data['importance']['ar'],
                'importance_en' => $data['importance']['en'],
                'difficulty_ar' => $data['difficulty']['ar'],
                'difficulty_en' => $data['difficulty']['en'],
            ]);

            foreach (['ar', 'en'] as $language) {
                $prevention->translations()->create([
                    'language' => $language,
                    'title' => $data['title'][$language],
                    'description' => $data['description'][$language],
                    'tips' => $data['tips'][$language],
                ]);
            }
        }
    }
}