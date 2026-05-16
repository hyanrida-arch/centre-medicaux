<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $doctorsData = [
            ['name'=>'Ahmed',   'last'=>'Benali',   'spec'=>'Cardiologie',       'fee'=>300, 'bio'=>'Cardiologue avec 15 ans d\'expérience.'],
            ['name'=>'Fatima',  'last'=>'Tazi',     'spec'=>'Pédiatrie',         'fee'=>250, 'bio'=>'Spécialiste en santé infantile.'],
            ['name'=>'Youssef', 'last'=>'Alami',    'spec'=>'Médecine générale', 'fee'=>150, 'bio'=>'Médecin généraliste disponible 6j/7.'],
            ['name'=>'Nadia',   'last'=>'El Fassi', 'spec'=>'Dermatologie',      'fee'=>280, 'bio'=>'Dermatologue certifiée.'],
            ['name'=>'Karim',   'last'=>'Ouali',    'spec'=>'Orthopédie',        'fee'=>350, 'bio'=>'Spécialiste en chirurgie orthopédique.'],
        ];

        $doctorUsers = [];
        foreach ($doctorsData as $d) {
            $user = User::create([
                'first_name' => $d['name'],
                'last_name'  => $d['last'],
                'email'      => strtolower($d['name']).'.'.strtolower(str_replace(' ','',$d['last'])).'@medecin.ma',
                'password'   => Hash::make('password'),
                'role'       => 'doctor',
            ]);
            $doctor = Doctor::create([
                'user_id'          => $user->id,
                'specialization'   => $d['spec'],
                'consultation_fee' => $d['fee'],
                'biography'        => $d['bio'],
                'is_available'     => true,
            ]);
            $doctorUsers[] = ['user' => $user, 'doctor' => $doctor];
        }

        $patients = [];
        foreach ([['Mohammed','Idrissi'],['Sara','Chraibi'],['Hassan','Benmoussa']] as $p) {
            $patients[] = User::create([
                'first_name' => $p[0],
                'last_name'  => $p[1],
                'email'      => strtolower($p[0]).'.'.strtolower($p[1]).'@patient.ma',
                'password'   => Hash::make('password'),
                'role'       => 'patient',
            ]);
        }

        Appointment::create(['patient_id'=>$patients[0]->id,'doctor_id'=>$doctorUsers[0]['doctor']->id,'appointment_date'=>now()->addDays(2),'appointment_time'=>'09:00','status'=>'pending','reason'=>'Contrôle cardiaque annuel.']);
        Appointment::create(['patient_id'=>$patients[1]->id,'doctor_id'=>$doctorUsers[1]['doctor']->id,'appointment_date'=>now()->subDays(5),'appointment_time'=>'14:30','status'=>'completed','reason'=>'Vaccination enfant.','notes'=>'Vaccination effectuée. RDV dans 6 mois.']);
        Appointment::create(['patient_id'=>$patients[2]->id,'doctor_id'=>$doctorUsers[2]['doctor']->id,'appointment_date'=>now()->addDay(),'appointment_time'=>'10:00','status'=>'accepted','reason'=>'Consultation générale.']);

        Message::create(['sender_id'=>$patients[0]->id,'receiver_id'=>$doctorUsers[0]['user']->id,'body'=>'Bonjour Docteur, j\'aimerais des renseignements sur le rendez-vous.','is_read'=>false]);
        Message::create(['sender_id'=>$doctorUsers[0]['user']->id,'receiver_id'=>$patients[0]->id,'body'=>'Bonjour ! Bien sûr, n\'hésitez pas à me poser vos questions.','is_read'=>true]);

        $this->command->info('✅ DB peuplée! Médecin: ahmed.benali@medecin.ma | Patient: mohammed.idrissi@patient.ma | Pass: password');
    }
}
