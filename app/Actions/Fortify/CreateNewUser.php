<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Transaction;
use App\Models\AccountNumber;
use Laravel\Jetstream\Jetstream;
use App\Models\SubscriptionPackage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
         Validator::make($input, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'cni' => ['required', 'string', 'max:255'],
            'id_package' => ['integer'],
            'id_account_type' => ['required', 'integer'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();


        $user = User::create([
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'phone_number' => $input['phone_number'],
            'address' => $input['address'],
            'cni' => $input['cni'],
        ]);

          // Créer un numéro de compte pour l'utilisateur
        $accountNumber = $this->generateAccountNumber($input['id_account_type']);


         // Créer un nouvel enregistrement dans la table AccountNumber
         AccountNumber::create([
            'id_user' => $user->id,
            'id_account_type' => $input['id_account_type'],
            'account_number' =>$accountNumber, // Méthode pour générer le numéro de compte
            'status' => 'inactive', // statut par défaut
    ]);

      if (isset($input['id_package']) && !empty($input['id_package'])) {
        // Créer un nouvel enregistrement dans la table SubscriptionPackage
        SubscriptionPackage::create([
            'account_number' => $accountNumber, // Remplacez $accountNumber par le numéro de compte généré
            'id_package' => $input['id_package'],
            ]);

      }

       // Méthode pour effectuer le transfert

        Transaction::create([
            'account_number_from' => "53795691759", // Compte source (s'il s'agit d'un transfert externe)
            'account_number_to' => $accountNumber, // Compte de destination
            'id_user' => 1, // Guichet par defaut
            'amount' => 10000, // Montant du transfert
            'reason' => 'Transfert initial - Ouverture compte', // Raison du transfert
        ]);


        return $user;

    }

     // Méthode pour générer un numéro de compte
     private function generateAccountNumber($id_account_type)
     {
         // Définir le préfixe et le suffixe en fonction du type de compte
         $prefix = '';
         $suffix = '';
         if ($id_account_type == 1) {
             $prefix = '537';
             $suffix = '59';
         } elseif ($id_account_type == 2) {
             $prefix = '630';
             $suffix = '43';
         }

         // Générer les chiffres aléatoires pour les parties centrales
         $middleDigits = '';
         $remainingLength = 11 - strlen($prefix) - strlen($suffix);
         for ($i = 0; $i < $remainingLength; $i++) {
             $middleDigits .= mt_rand(0, 9);
         }

         // Concaténer le préfixe, les chiffres aléatoires et le suffixe pour former le numéro de compte complet
         $accountNumber = $prefix . $middleDigits . $suffix;

         // Vérifier si le numéro de compte est unique
         $isUnique = AccountNumber::where('account_number', $accountNumber)->doesntExist();

         // Si le numéro de compte n'est pas unique, régénérer jusqu'à ce qu'un numéro unique soit obtenu
         while (!$isUnique) {
             // Regénérer les chiffres aléatoires du milieu
             $middleDigits = '';
             for ($i = 0; $i < $remainingLength; $i++) {
                 $middleDigits .= mt_rand(0, 9);
             }

             // Réassigner le numéro de compte
             $accountNumber = $prefix . $middleDigits . $suffix;

             // Vérifier à nouveau l'unicité
             $isUnique = AccountNumber::where('account_number', $accountNumber)->doesntExist();
         }

         return $accountNumber;
     }


}
