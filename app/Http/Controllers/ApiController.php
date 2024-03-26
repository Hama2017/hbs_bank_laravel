<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Package;
use App\Models\CreditCard;
use App\Models\AccountType;
use App\Models\Beneficiary;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\AccountNumber;
use App\Models\SubscriptionPackage;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{

    public function getBeneficiaries(Request $request)
{
    // Récupérer les bénéficiaires de l'utilisateur actuel
    $beneficiaries = Beneficiary::where('id_user', auth()->id())->with('user')->get();

    // Retourner les bénéficiaires sous forme de réponse JSON
    return response()->json(['beneficiaries' => $beneficiaries], 200);
}


    public function transfer(Request $request)
{
    // Validation des données reçues
    $request->validate([
        'amount' => 'required|numeric|min:0',
        'beneficiary' => 'required|exists:beneficiaries,account_number'
    ]);

    // Récupérer le compte courant de l'utilisateur actuel
    $currentAccount = auth()->user()->accountNumbers()->where('id_account_type', 1)->first();

    // Vérifier si le solde du compte est suffisant pour le transfert
    if ($currentAccount->balance() < $request->amount) {
        return response()->json(['error' => 'Solde insuffisant dans le compte'], 400);
    }

    // Effectuer le transfert
    // ...

    // Retourner une réponse JSON
    return response()->json(['message' => 'Transfert effectué avec succès'], 200);
}



    public function addBeneficiary(Request $request)
    {
        $user = Auth::user();


        $currentAccountNumber = $user->accountNumbers()->where('id_account_type', 1)->value('account_number');

        // Validation des données reçues
        $request->validate([
            'accountNumber' => 'required|string'
        ]);

        // Vérifier si le numéro de compte existe
        $existingAccount = AccountNumber::where('account_number', $request->accountNumber)->first();

        // Vérifier si le compte appartient à l'utilisateur actuel
        if ($existingAccount && $request->accountNumber==$currentAccountNumber) {
            return response()->json(['error' => 'Vous ne pouvez pas ajouter votre propre compte comme bénéficiaire'], 400);
        }

        // Vérifier si le compte existe et s'il appartient à un compte courant (commence par 537)
        if (!$existingAccount || substr($request->accountNumber, 0, 3) !== '537') {
            return response()->json(['error' => 'Le numéro de compte n\'est pas valide'], 400);
        }

        // Vérifier si le bénéficiaire existe déjà pour cet utilisateur
        $existingBeneficiary = Beneficiary::where('id_user', auth()->id())
                                           ->where('account_number', $request->accountNumber)
                                           ->exists();
        if ($existingBeneficiary) {
            return response()->json(['error' => 'Ce bénéficiaire existe déjà'], 400);
        }

        // Créer un nouveau bénéficiaire
        $beneficiary = Beneficiary::create([
            'id_user' => auth()->id(),
            'account_number' => $request->accountNumber
        ]);

        // Retourner une réponse JSON
        return response()->json(['message' => 'Bénéficiaire ajouté avec succès'], 200);
    }




    public function getVirtualCards(Request $request)
{
    // Récupérer l'utilisateur connecté
    $user = $request->user();

    // Récupérer le numéro de compte courant de l'utilisateur connecté
    $currentAccountNumber = $user->accountNumbers()->where('id_account_type', 1)->value('account_number');

    // Vérifier si un numéro de compte courant a été trouvé
    if (!$currentAccountNumber) {
        return response()->json(['error' => 'Aucun compte courant trouvé pour cet utilisateur'], 404);
    }

    // Récupérer les cartes virtuelles associées au numéro de compte courant de l'utilisateur actuel
    $virtualCards = CreditCard::where('account_number', $currentAccountNumber)->get();

    // Retourner les cartes virtuelles sous forme de réponse JSON
    return response()->json(['cards' => $virtualCards], 200);
}



    // Fonction pour générer un numéro de carte de crédit aléatoire
public function generateCreditCardNumber() {
    $prefixes = ['4', '51', '52', '53', '54', '55']; // Exemples de préfixes de cartes Visa et Mastercard
    $prefix = $prefixes[array_rand($prefixes)]; // Sélectionnez un préfixe aléatoire
    $length = 16; // Longueur du numéro de carte de crédit
    $number = $prefix;

    // Remplissez le reste du numéro de carte avec des chiffres aléatoires
    for ($i = strlen($prefix); $i < $length - 1; $i++) {
        $number .= mt_rand(0, 9);
    }

    // Calculez la somme de contrôle (Luhn)
    $checksum = 0;
    for ($i = strlen($number) - 1; $i >= 0; $i--) {
        $digit = intval($number[$i]);
        if ($i % 2 == strlen($number) % 2) {
            $digit *= 2;
            $digit = ($digit > 9) ? $digit - 9 : $digit;
        }
        $checksum += $digit;
    }

    // Ajoutez le chiffre de contrôle
    $checksum %= 10;
    $checksum = ($checksum == 0) ? 0 : 10 - $checksum;
    $number .= strval($checksum);

    return $number;
}

// Fonction pour générer un code de sécurité CVV aléatoire (généralement à 3 ou 4 chiffres)
public function generateCVV() {
    $length = 3; // Longueur du CVV (peut être 3 ou 4)
    $cvv = '';
    for ($i = 0; $i < $length; $i++) {
        $cvv .= mt_rand(0, 9);
    }
    return $cvv;
}

// Fonction pour générer une date d'expiration aléatoire
public function generateExpiryDate() {
    $currentYear = date('Y');
    $currentMonth = date('m');

    // Générer une année aléatoire dans les 2 prochaines années
    $year = $currentYear + mt_rand(0, 2);

    // Générer un mois aléatoire
    $month = ($year == $currentYear) ? mt_rand($currentMonth, 12) : mt_rand(1, 12);

    // Formater la date d'expiration au format MM/YY
    $expiryDate = sprintf('%02d/%02d', $month, $year % 100);

    return $expiryDate;
}



public function createCreditCard(Request $request)
{
    // Valider les données de la requête
    $request->validate([
        'amount' => 'required|numeric|min:0', // Montant requis pour créer la carte de crédit
    ]);

    // Récupérer l'utilisateur authentifié
    $user = $request->user();

    // Récupérer le compte courant de l'utilisateur
    $currentAccount = $user->accountNumbers()->where('id_account_type', 1)->first();

    // Vérifier si le compte courant existe
    if (!$currentAccount) {
        return response()->json(['error' => 'Aucun compte courant trouvé pour cet utilisateur'], 404);
    }

    // Vérifier si le solde du compte est suffisant
    if ($currentAccount->balance() < $request->amount) {
        return response()->json(['error' => 'Solde insuffisant dans le compte courant'], 400);
    }

    // Créer la carte de crédit
    $creditCard = CreditCard::create([
        'account_number' => $currentAccount->account_number,
        'number' => $this->generateCreditCardNumber(),
        'cvv' => $this->generateCVV(),
        'exp_date' => $this->generateExpiryDate(),
        'amount' => $request->amount,
    ]);

    Transaction::create([
        'account_number_from' => $currentAccount->account_number, // Compte source (s'il s'agit d'un transfert externe)
        'account_number_to' => $currentAccount->account_number, // Compte de destination
        'id_user' => auth()->user()->id, // Guichet par defaut
        'amount' => -$request->amount, // Montant du transfert
        'reason' => 'Création de carte virtuelle', // Raison du transfert
    ]);

    // Débiter le montant du solde du compte courant
    // Vous devrez peut-être implémenter cette fonctionnalité selon votre logique métier

    // Retourner une réponse JSON avec un message de succès
    return response()->json(['message' => 'Carte de crédit créée avec succès'], 200);
}




    public function currentAccountBalance(Request $request)
    {
        $user = $request->user();
        $currentAccount = $user->accountNumbers()->where('id_account_type', 1)->first();

        if (!$currentAccount) {
            return response()->json(['error' => 'Aucun compte courant trouvé pour cet utilisateur'], 404);
        }

        $balance = $currentAccount->balance();

        return response()->json(['balance' => $balance], 200);
    }
    public function currentAccountTransactions(Request $request)
    {
        $user = $request->user();
        $currentAccount = $user->accountNumbers()->where('id_account_type', 1)->first();

        if (!$currentAccount) {
            return response()->json(['error' => 'Aucun compte courant trouvé pour cet utilisateur'], 404);
        }

        // Rechercher les transactions où le compte source ou de destination est le compte courant
        $transactions = Transaction::where('account_number_from', $currentAccount->account_number)
            ->orWhere('account_number_to', $currentAccount->account_number)
            ->latest()
            ->get();

              // Formater les dates des transactions
        foreach ($transactions as $transaction) {
            $transaction->formatted_date = Carbon::parse($transaction->created_at)->format('d/m/Y H:i:s');
        }

        return response()->json(['transactions' => $transactions], 200);
    }

    public function savingsAccountBalance()
    {
        $user = Auth::user();
        $savingsAccount = $user->accountNumbers()->where('id_account_type', 2)->first();

        if (!$savingsAccount) {
            return response()->json(['error' => 'Aucun compte d\'épargne trouvé pour cet utilisateur'], 404);
        }

        return response()->json(['balance' => $savingsAccount->balance()], 200);
    }

    public function savingsAccountTransactions()
    {
        $user = Auth::user();
        $savingsAccount = $user->accountNumbers()->where('id_account_type', 2)->first();

        if (!$savingsAccount) {
            return response()->json(['error' => 'Aucun compte d\'épargne trouvé pour cet utilisateur'], 404);
        }

        $transactions = Transaction::where('account_number_from', $savingsAccount->account_number)
            ->orWhere('account_number_to', $savingsAccount->account_number)
            ->latest()
            ->get();


                         // Formater les dates des transactions
        foreach ($transactions as $transaction) {
            $transaction->formatted_date = Carbon::parse($transaction->created_at)->format('d/m/Y H:i:s');
        }


        return response()->json(['transactions' => $transactions], 200);
    }

    public function getAccountTypes()
    {
        $accountTypes = AccountType::all();
        return response()->json(['accountTypes' => $accountTypes]);
    }

    public function getPackages(Request $request)
    {
        $accountType = $request->input('account_type');
        if ($accountType == 1) {
            $packages = Package::all();
            return response()->json(['packages' => $packages]);
        } else {
            // Si le type de compte est "epargne", renvoyer une réponse vide
            return response()->json(['packages' => []]);
        }
    }


    public function openCurrentAccount(Request $request)
{
    // Valider les données du formulaire
    $request->validate([
        'package' => 'required|exists:packages,id',
    ]);

    // Créer le compte courant
    $currentAccount = new AccountNumber();
    $currentAccount->id_user = auth()->user()->id;
    $currentAccount->id_account_type = 1; // Supposons que l'id pour le compte courant soit 1
    $currentAccount->account_number = $this->generateAccountNumber(1); // Générer le numéro de compte
    $currentAccount->status = 'active'; // Par défaut, le compte est actif
    $currentAccount->save();

    // Associer le package au compte courant
    SubscriptionPackage::create([
        'account_number' => $currentAccount->account_number, // Assurez-vous d'avoir accès à $currentAccount ici
        'id_package' => $request->package,
    ]);

        // Méthode pour effectuer le transfert

        Transaction::create([
            'account_number_from' => "53795691759", // Compte source (s'il s'agit d'un transfert externe)
            'account_number_to' => $currentAccount->account_number, // Compte de destination
            'id_user' => 1, // Guichet par defaut
            'amount' => 10000, // Montant du transfert
            'reason' => 'Transfert initial - Ouverture compte', // Raison du transfert
        ]);



    // Retourner une réponse JSON
    return response()->json(['message' => 'Compte courant ouvert avec succès', 'account' => $currentAccount]);
}


public function openSavingsAccount(Request $request)
    {
        // Créer un nouveau compte d'épargne
    $currentAccount = new AccountNumber();
    $currentAccount->id_user = auth()->user()->id;
    $currentAccount->id_account_type = 2; // Supposons que l'id pour le compte courant soit 1
    $currentAccount->account_number = $this->generateAccountNumber(2); // Générer le numéro de compte
    $currentAccount->status = 'active'; // Par défaut, le compte est actif
    $currentAccount->save();

    // Méthode pour effectuer le transfert

    Transaction::create([
        'account_number_from' => "53795691759", // Compte source (s'il s'agit d'un transfert externe)
        'account_number_to' => $currentAccount->account_number, // Compte de destination
        'id_user' => 1, // Guichet par defaut
        'amount' => 10000, // Montant du transfert
        'reason' => 'Transfert initial - Ouverture compte', // Raison du transfert
    ]);

        // Retourner une réponse JSON
        return response()->json(['message' => 'Compte d\'épargne créé avec succès'], 200);
    }

  // Méthode pour générer un numéro de compte
  public function generateAccountNumber($id_account_type)
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
