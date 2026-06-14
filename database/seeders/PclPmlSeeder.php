<?php

namespace Database\Seeders;

use App\Models\PclPml;
use Illuminate\Database\Seeder;

class PclPmlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mappings = [
            ['pcl_email' => 'injiliattngkngwalo@gmail.com', 'pml_email' => 'novrysariu1@gmail.com'],
            ['pcl_email' => 'ayuanatakasabare80@gmail.com', 'pml_email' => 'novrysariu1@gmail.com'],
            ['pcl_email' => 'duyohmarini@gmail.com', 'pml_email' => 'novrysariu1@gmail.com'],
            ['pcl_email' => 'kasehungchrisnaldy@gmail.com', 'pml_email' => 'novrysariu1@gmail.com'],
            ['pcl_email' => 'royughude@gmail.com', 'pml_email' => 'novrysariu1@gmail.com'],
            ['pcl_email' => 'megatogelang@gmail.com', 'pml_email' => 'ronalkakumboti07@gmail.com'],
            ['pcl_email' => 'officialtbr3@gmail.com', 'pml_email' => 'ronalkakumboti07@gmail.com'],
            ['pcl_email' => 'vitikerenk@gmail.com', 'pml_email' => 'ronalkakumboti07@gmail.com'],
            ['pcl_email' => 'kristinmanuho270783@gmail.com', 'pml_email' => 'ronalkakumboti07@gmail.com'],
            ['pcl_email' => 'paredadiana@gmail.com', 'pml_email' => 'ronalkakumboti07@gmail.com'],
            ['pcl_email' => 'axtoryahamel95@gmail.com', 'pml_email' => 'ronalkakumboti07@gmail.com'],
            ['pcl_email' => 'fernandamanuho@gmail.com', 'pml_email' => 'ansyesindyzanda@gmail.com'],
            ['pcl_email' => 'yolandaolvie@gmail.com', 'pml_email' => 'ansyesindyzanda@gmail.com'],
            ['pcl_email' => 'marcianosampingan46@gmail.com', 'pml_email' => 'ansyesindyzanda@gmail.com'],
            ['pcl_email' => 'pitresmakanoneng13021994@gmail.com', 'pml_email' => 'ansyesindyzanda@gmail.com'],
            ['pcl_email' => 'mangebersandy@gmail.com', 'pml_email' => 'ansyesindyzanda@gmail.com'],
            ['pcl_email' => 'tambirangcarolina@gmail.com', 'pml_email' => 'ansyesindyzanda@gmail.com'],
            ['pcl_email' => 'makienggungt@gmail.com', 'pml_email' => 'vendytatimu@gmail.com'],
            ['pcl_email' => 'mahmudisra@gmail.com', 'pml_email' => 'vendytatimu@gmail.com'],
            ['pcl_email' => 'afrisya30bogar@gmail.com', 'pml_email' => 'vendytatimu@gmail.com'],
            ['pcl_email' => 'alfitricahyasukmajanis12@gmail.com', 'pml_email' => 'vendytatimu@gmail.com'],
            ['pcl_email' => 'nadyabukanaung14@gmail.com', 'pml_email' => 'vendytatimu@gmail.com'],
            ['pcl_email' => 'dellyantosikape@gmail.com', 'pml_email' => 'nonpatara0710@gmail.com'],
            ['pcl_email' => 'santibalo16@gmail.com', 'pml_email' => 'nonpatara0710@gmail.com'],
            ['pcl_email' => 'tmananggel@gmail.com', 'pml_email' => 'nonpatara0710@gmail.com'],
            ['pcl_email' => 'syliamakahinda96@gmail.com', 'pml_email' => 'nonpatara0710@gmail.com'],
            ['pcl_email' => 'inggritvinolia@gmail.com', 'pml_email' => 'nonpatara0710@gmail.com'],
            ['pcl_email' => 'nolvipangumpia@gmail.com', 'pml_email' => 'nonpatara0710@gmail.com'],
            ['pcl_email' => 'yosibahansang140186@gmail.com', 'pml_email' => 'stevan.gonggalang76@gmail.com'],
            ['pcl_email' => 'onisyntapimpin85@gmail.com', 'pml_email' => 'stevan.gonggalang76@gmail.com'],
            ['pcl_email' => 'jilastriko@gmail.com', 'pml_email' => 'stevan.gonggalang76@gmail.com'],
            ['pcl_email' => 'dewiansalindeho891@gmail.com', 'pml_email' => 'stevan.gonggalang76@gmail.com'],
            ['pcl_email' => 'eiverkansil1@gmail.com', 'pml_email' => 'stevan.gonggalang76@gmail.com'],
            ['pcl_email' => 'anitalimbe97@gmail.com', 'pml_email' => 'ketsiapandelisang0668@gmail.com'],
            ['pcl_email' => 'Indrilalisang29@gmail.com', 'pml_email' => 'ketsiapandelisang0668@gmail.com'],
            ['pcl_email' => 'yusufkalangit373@mail.com', 'pml_email' => 'ketsiapandelisang0668@gmail.com'],
            ['pcl_email' => 'jeinmalohing23@gmail.com', 'pml_email' => 'ketsiapandelisang0668@gmail.com'],
            ['pcl_email' => 'hermitakakalang@gmail.com', 'pml_email' => 'ketsiapandelisang0668@gmail.com'],
            ['pcl_email' => 'susan.tatambihe@gmail.com', 'pml_email' => 'ketsiapandelisang0668@gmail.com'],
            ['pcl_email' => 'jessicawilade97@gmail.com', 'pml_email' => 'bob.mokodompis@gmail.com'],
            ['pcl_email' => 'sandratampilang12@gmail.com', 'pml_email' => 'bob.mokodompis@gmail.com'],
            ['pcl_email' => 'harrygereuw@gmail.com', 'pml_email' => 'bob.mokodompis@gmail.com'],
            ['pcl_email' => 'marthinauditya@gmail.com', 'pml_email' => 'bob.mokodompis@gmail.com'],
            ['pcl_email' => 'anggrainisagilateng@gmail.com', 'pml_email' => 'bob.mokodompis@gmail.com'],
            ['pcl_email' => 'jliatahi93@gmail.com', 'pml_email' => 'mathildaderek27@gmail.com'],
            ['pcl_email' => 'bebmanoarfa30@gmail.com', 'pml_email' => 'mathildaderek27@gmail.com'],
            ['pcl_email' => 'susyebawoleh@gmail.com', 'pml_email' => 'mathildaderek27@gmail.com'],
            ['pcl_email' => 'oliviakahusadi1994@gmail.com', 'pml_email' => 'mathildaderek27@gmail.com'],
            ['pcl_email' => 'angelabwmng@gmail.com', 'pml_email' => 'mathildaderek27@gmail.com'],
            ['pcl_email' => 'stedalinsalekede@gmail.com', 'pml_email' => 'derekvalentina25@gmail.com'],
            ['pcl_email' => 'lifakabuhung1809@gmail.com', 'pml_email' => 'derekvalentina25@gmail.com'],
            ['pcl_email' => 'noveliagrifitmakikama@gmail.com', 'pml_email' => 'derekvalentina25@gmail.com'],
            ['pcl_email' => 'anggrainisagilateng1009@gmail.com', 'pml_email' => 'derekvalentina25@gmail.com'],
            ['pcl_email' => 'Jezzline.eugene@gmail.com', 'pml_email' => 'derekvalentina25@gmail.com'],
            ['pcl_email' => 'zefanya.umbaseng@gmail.com', 'pml_email' => 'lomendehed@gmail.com'],
            ['pcl_email' => 'tahlia0009@gmail.com', 'pml_email' => 'lomendehed@gmail.com'],
            ['pcl_email' => 'karyapemudasakti@gmail.com', 'pml_email' => 'lomendehed@gmail.com'],
            ['pcl_email' => 'reckythiro28@gmail.com', 'pml_email' => 'lomendehed@gmail.com'],
            ['pcl_email' => 'edrouldderek@gmail.com', 'pml_email' => 'lomendehed@gmail.com'],
            ['pcl_email' => 'lombonereynaldo@gmail.com', 'pml_email' => 'imeldahoroni7@gmail.com'],
            ['pcl_email' => 'ariske2505@gmail.com', 'pml_email' => 'imeldahoroni7@gmail.com'],
            ['pcl_email' => 'achelthomas2211@gmail.com', 'pml_email' => 'imeldahoroni7@gmail.com'],
            ['pcl_email' => 'maurendevina09@gmail.com', 'pml_email' => 'imeldahoroni7@gmail.com'],
        ];

        foreach ($mappings as $mapping) {
            PclPml::updateOrCreate(
                ['pcl_email' => $mapping['pcl_email']],
                ['pml_email' => $mapping['pml_email']]
            );
        }
    }
}
