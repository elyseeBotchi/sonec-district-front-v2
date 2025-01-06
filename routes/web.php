<?php

use App\Http\Controllers\Admin\AgentsController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Admin\LoginController;
use \App\Http\Controllers\Admin\CollaboratorsController;
use App\Http\Controllers\Admin\EntitesController;
use App\Http\Controllers\Admin\FacturationsController;
use \App\Http\Controllers\Admin\ModuleController;
use \App\Http\Controllers\Admin\PermissionController;
use \App\Http\Controllers\Admin\RolesController;
use \App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\RubriquesController;
use App\Http\Controllers\ControlesController;
use App\Http\Controllers\Customer\CustomerFacturationsController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Customer\UsersController;
use \App\Http\Controllers\Customer\LoginController as CustomerLoginController;
use App\Http\Controllers\Customer\ServicesController;
use App\Http\Controllers\Admin\ServicesController as AdminServicesController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/paiement/standby/{uuid}', [LandingController::class, 'standby_return'])->name('paiement.standby');
Route::get('/paiement/succes/{uuid}', [LandingController::class, 'success_return'])->name('paiement.success');
Route::get('/paiement/erreur/{uuid}', [LandingController::class, 'success_return'])->name('paiement.error');

Route::get('/', [LandingController::class, 'index2'])->name('welcome.index');
//Route::get('/index2', [LandingController::class, 'index2'])->name('welcome.index2');


/* ########################## FIN ROUTE ADMIN ################################## */

    Route::post('panel/logout', [LoginController::class, 'logout'])->name('panel.logout');
    Route::post('controle/logout', [LoginController::class, 'controle_logout'])->name('controle.logout');
    Route::get('panel/login', [LoginController::class, 'login'])->name('panel.login');
    Route::post('panel/connexion', [LoginController::class, 'Connexion'])->name('panel.connexion');

    Route::get('panel/otp/resend', [LoginController::class, 'resend_otp'])->name('panel.otp.resend');

    Route::get('panel/mot/de/passe/oublie', [LoginController::class, 'password_forget'])->name('panel.forget.password');
    Route::post('panel/password/email', [LoginController::class, 'send_password_forget_email'])->name('panel.password.email');
    Route::get('panel/reset/password/{token}', [LoginController::class, 'password_reset'])->name('panel.reset.password');
    Route::post('panel/password/confirm', [LoginController::class, 'password_confirm'])->name('panel.password.confirm');

    Route::get('panel/otp', [LoginController::class, 'otp'])->name('panel.otp');
    Route::post('panel/otp/connexion', [LoginController::class, 'Otp_Connexion'])->name('panel.otp.submit');

    Route::prefix('panel')->middleware(['IsConnect'])->group(function () {

        Route::get('home', [LoginController::class, 'index'])->name('panel.home');
        Route::get('dashboard', [LoginController::class, 'index'])->name('panel.dashboard');


        Route::prefix('modules')->group(function(){
            Route::get('findAll', [ModuleController::class, 'findAll'])->name('panel.autorisations.modules.findAll');
            Route::get('index', [ModuleController::class, 'index'])->name('panel.autorisations.modules.index');
            Route::post('store', [ModuleController::class, 'store'])->name('panel.autorisations.modules.store');
            Route::get('/{uuid}/edit', [ModuleController::class, 'edit'])->name('panel.autorisations.modules.edit');
            Route::post('/{uuid}/update', [ModuleController::class, 'update'])->name('panel.autorisations.modules.update');
            Route::get('/{uuid}/delete', [ModuleController::class, 'delete'])->name('panel.autorisations.modules.delete');

            Route::prefix('permissions')->group(function(){
                Route::get('{module_uuid}/findAll', [PermissionController::class, 'findAll'])->name('panel.autorisations.permissions.findAll');
                Route::get('{module_uuid}/index', [PermissionController::class, 'index'])->name('panel.autorisations.permissions.index');
                Route::post('{module_uuid}/store', [PermissionController::class, 'store'])->name('panel.autorisations.permissions.store');
                Route::get('/{uuid}/edit', [PermissionController::class, 'edit'])->name('panel.autorisations.permissions.edit');
                Route::post('/{uuid}/update', [PermissionController::class, 'update'])->name('panel.autorisations.permissions.update');
                Route::get('/{uuid}/delete', [PermissionController::class, 'delete'])->name('panel.autorisations.permissions.delete');
            });


        });

        Route::prefix('roles')->group(function(){
            Route::get('/findAll', [\App\Http\Controllers\Admin\RolesController::class, 'findAll'])->name('panel.autorisations.roles.findAll');
            Route::get('index', [RolesController::class, 'index'])->name('panel.autorisations.roles.index');
            Route::post('store', [RolesController::class, 'store'])->name('panel.autorisations.roles.store');
            Route::get('/{uuid}/edit', [RolesController::class, 'edit'])->name('panel.autorisations.roles.edit');
            Route::post('/{uuid}/update', [RolesController::class, 'update'])->name('panel.autorisations.roles.update');
            Route::get('/{uuid}/delete', [RolesController::class, 'delete'])->name('panel.autorisations.roles.delete');
            Route::get('/{uuid}/permissions', [RolesController::class, 'permissionIndex'])->name('panel.autorisations.roles.permissions.index');
            Route::get('/{uuid}/permissions/update', [RolesController::class, 'permissionUpdate'])->name('panel.autorisations.roles.permissions.update');
        });

        Route::prefix('collaborateurs')->group(function(){
            Route::get('index', [CollaboratorsController::class, 'index'])->name('panel.autorisations.collaborateurs.index');
            Route::post('store', [CollaboratorsController::class, 'store'])->name('panel.autorisations.collaborateurs.store');
            Route::get('/{uuid}/show', [CollaboratorsController::class, 'show'])->name('panel.autorisations.collaborateurs.show');
            Route::get('/{uuid}/edit', [CollaboratorsController::class, 'edit'])->name('panel.autorisations.collaborateurs.edit');
            Route::post('/{uuid}/update', [CollaboratorsController::class, 'update'])->name('panel.autorisations.collaborateurs.update');
            Route::post('/{uuid}/add-role', [CollaboratorsController::class, 'storeRole'])->name('panel.autorisations.collaborateurs.add_role');
            Route::get('/{uuid}/change', [CollaboratorsController::class, 'change'])->name('panel.autorisations.collaborateurs.change');
            Route::get('/{uuid}/officeChangeStatus', [CollaboratorsController::class, 'officeChangeStatus']);
            Route::get('{uuid}/findAllOffice', [CollaboratorsController::class, 'findAllOffice'])->name('panel.autorisation.collaborateurs.find_all_office');

            Route::get('/autorisations/findAll', [CollaboratorsController::class, 'findAll'])->name('panel.autorisations.collaborateurs.findAll');
            Route::get('/autorisations/{uuid}/findAllOffice', [CollaboratorsController::class, 'findAllOffice'])->name('panel.autorisations.collaborateurs.findAllOffice');

        });

        Route::prefix('agents')->group(function(){
            Route::get('index', [AgentsController::class, 'index'])->name('panel.autorisations.agents.index');
            Route::post('store', [AgentsController::class, 'store'])->name('panel.autorisations.agents.store');
            Route::get('/{uuid}/show', [AgentsController::class, 'show'])->name('panel.autorisations.agents.show');
            Route::get('/{uuid}/edit', [AgentsController::class, 'edit'])->name('panel.autorisations.agents.edit');
            Route::post('/{uuid}/update', [AgentsController::class, 'update'])->name('panel.autorisations.agents.update');
            Route::get('/{uuid}/change', [AgentsController::class, 'change'])->name('panel.autorisations.agents.change');

            Route::get('/autorisations/findAll', [AgentsController::class, 'findAll'])->name('panel.autorisations.agents.findAll');
  
        });

        
        Route::prefix('activity/agents')->group(function(){
            Route::get('index', [AgentsController::class, 'activity_dashboard'])->name('panel.autorisations.activity.agents.index');
            Route::get('/load/all/activities', [AgentsController::class, 'activity_load'])->name('panel.autorisations.activity.agents.findAll');
            Route::post('/load/search/activities', [AgentsController::class, 'activity_find'])->name('panel.autorisations.activity.agents.search');
        });

        Route::get('/load/all/activities/statistique', [AgentsController::class, 'activity_statistique'])->name('panel.autorisations.activity.findAll.statistique');


        Route::prefix('securite')->group(function(){
            Route::get('compte', [CollaboratorsController::class, 'securite'])->name('panel.securite.compte');
            Route::post('compte/update/data', [CollaboratorsController::class, 'updateAccount'])->name('panel.securite.compte.update.data');
            Route::post('compte/update/password', [CollaboratorsController::class, 'updatePassword'])->name('panel.securite.compte.update.password');
            Route::post('compte/update/avatar', [CollaboratorsController::class, 'uploadAvatar'])->name('panel.securite.compte.update.avatar');
        });

        Route::prefix('entite')->group(function(){
            Route::get('liste', [EntitesController::class, 'index'])->name('panel.autorisations.entite.index');
            Route::get('findAll', [EntitesController::class, 'findAll'])->name('panel.autorisations.entite.findAll');
            Route::get('findOneConfig/{uuid}', [EntitesController::class, 'findOneConfig'])->name('panel.autorisations.entite.findOneConfig');
            Route::get('findOne/{uuid}', [EntitesController::class, 'findOne'])->name('panel.autorisations.entite.findOne');
      
            Route::post('gabari/store', [EntitesController::class, 'gabariStore'])->name('panel.autorisations.entite.gabari.store');

            Route::post('store', [EntitesController::class, 'store'])->name('panel.autorisations.entite.store');
            Route::get('show/{uuid}', [EntitesController::class, 'show'])->name('panel.autorisations.entite.show');
            Route::post('update', [EntitesController::class, 'update'])->name('panel.autorisations.entite.update');
            Route::post('delete', [EntitesController::class, 'delete'])->name('panel.autorisations.entite.delete');
            Route::get('gabari/findAll/{uuid}', [EntitesController::class, 'gabariFindAll'])->name('panel.autorisations.entite.gabari.find_all');
            Route::get('gabari/validate/{uuid}/{status}', [EntitesController::class, 'validateFile'])->name('panel.autorisations.entite.gabari.find_all');

            Route::get('gabari/model/{uuid}', [EntitesController::class, 'gabari_model'])->name('panel.autorisations.entite.gabari.model');
            Route::get('gabari/{uuid}', [EntitesController::class, 'gabari'])->name('panel.autorisations.entite.gabari');

            Route::prefix('rubrique')->group(function(){
                Route::post('store', [RubriquesController::class, 'store'])->name('panel.autorisations.entite.rubrique.store');
                Route::post('update', [RubriquesController::class, 'update'])->name('panel.autorisations.entite.rubrique.update');
                Route::post('delete', [RubriquesController::class, 'lock'])->name('panel.autorisations.entite.rubrique.delete');
                Route::get('findOneConfig/{uuid}', [RubriquesController::class, 'findOneConfig'])->name('panel.autorisations.entite.rubrique.findOneConfig');
                Route::prefix('facturation')->group(function(){
                    Route::post('store', [FacturationsController::class, 'store'])->name('panel.autorisations.entite.rubrique.facturation.store');
                    Route::post('delete', [FacturationsController::class, 'lock'])->name('panel.autorisations.entite.rubrique.facturation.delete');
                });

                Route::post('option/delete', [RubriquesController::class, 'lock'])->name('panel.autorisations.entite.rubrique.option.delete');

            });

        });

        
        Route::prefix('services')->group(function(){
            Route::get('show/{uuid}', [AdminServicesController::class, 'index'])->name('panel.autorisations.services.show.data');
            Route::get('taxes/findAll/{uuid}', [AdminServicesController::class, 'findAll'])->name('panel.autorisations.services.taxes.find_all');
            Route::get('taxes/detail/{uuid}/{entity_uuid}', [AdminServicesController::class, 'show'])->name('panel.autorisations.services.taxes.show');
            Route::get('taxes/validation/{uuid}/{entity_uuid}/{status}', [AdminServicesController::class, 'validation'])->name('panel.autorisations.services.taxes.validation');
            Route::get('taxes/statistique/{uuid}', [AdminServicesController::class, 'statistique'])->name('panel.autorisations.services.taxes.statistique');
            Route::post('taxes/search/findAll', [AdminServicesController::class, 'search'])->name('panel.autorisations.services.taxes.search');


            Route::get('rdv/{uuid}', [AdminServicesController::class, 'rendez_vous'])->name('panel.autorisations.services.rdv');
            Route::get('liste/rdv/{uuid}', [AdminServicesController::class, 'liste_rdv'])->name('panel.autorisations.services.liste.rdv');
            Route::get('taxes/rdv/statistique/{uuid}', [AdminServicesController::class, 'stat_rdv'])->name('panel.autorisations.services.taxes.rdv.statistique');
            Route::get('taxes/rdv/findAll/{uuid}', [AdminServicesController::class, 'rdv_findAll'])->name('panel.autorisations.services.taxes.rdv.find_all');

            Route::post('taxes/rdv/search', [AdminServicesController::class, 'rdv_search'])->name('panel.autorisations.services.taxes.rdv.search');
            Route::get('activite/{uuid}', [AdminServicesController::class, 'rdv_activite'])->name('panel.autorisations.services.activite');
            Route::get('taxes/rdv/today/activite/{uuid}', [AdminServicesController::class, 'rdv_today_activite'])->name('panel.autorisations.services.today.activite');

             
        });

                
        Route::prefix('statistique')->group(function(){
            Route::get('show/{uuid}/{type_stat}', [AdminServicesController::class, 'stat_dashboard'])->name('panel.autorisations.statistique.show.data');
            Route::get('data/count/{entity}', [AdminServicesController::class, 'stat_data'])->name('panel.autorisations.statistique.data');
            Route::get('data/rendezvous/{entity}/{rdv}', [AdminServicesController::class, 'data_rdv'])->name('panel.autorisations.statistique.data');
            Route::get('/findStatus/data/{status}/{paymode}/{entity}', [AdminServicesController::class, 'stat_find_data'])->name('panel.autorisations.statistique.find.data');
            Route::get('data/validation_j/{entity}/{day}', [AdminServicesController::class, 'data_validationJ'])->name('panel.autorisations.statistique.data.validateur');
            Route::get('data/validateur/{entity}', [AdminServicesController::class, 'data_validateur'])->name('panel.autorisations.statistique.data.validateur');
            
        });


        Route::prefix('customer')->group(function () {
            Route::get('/service/taxe/find_one/{uuid}/{entity_uuid}', [AdminServicesController::class, 'find_service'])->name('panel.customer.entities.taxe.find_service');
  
        });


    });

/* ########################## FIN ROUTE ADMIN ################################## */


    Route::prefix('customer')->group(function(){
        Route::post('connexion', [CustomerLoginController::class, 'Connexion'])->name('customer.connexion');

        Route::get('otp/resend', [CustomerLoginController::class, 'resend_otp'])->name('customer.otp.resend');
    
        Route::get('mot/de/passe/oublie', [CustomerLoginController::class, 'password_forget'])->name('customer.forget.password');
        Route::post('password/email', [CustomerLoginController::class, 'send_password_forget_email'])->name('customer.password.email');
        Route::get('reset/password/{token}', [CustomerLoginController::class, 'password_reset'])->name('customer.reset.password');
        Route::post('password/confirm', [CustomerLoginController::class, 'password_confirm'])->name('customer.password.confirm');
    
        Route::get('otp', [CustomerLoginController::class, 'otp'])->name('customer.otp');
        Route::post('otp/connexion', [CustomerLoginController::class, 'Otp_Connexion'])->name('customer.otp.submit');
    

        Route::get('/entite/findAll', [ServicesController::class, 'findAllEntite'])->name('customer.entities.findAll');
        Route::get('/services/findOne/{uuid}', [ServicesController::class, 'findOneEntite'])->name('customer.entities.findOne');
        Route::get('/services/taxe/entetes/{uuid}', [ServicesController::class, 'entete'])->name('customer.entities.taxe.entete');
    
        
    });

    Route::prefix('landing')->group(function(){
        Route::get('/services/rubrique/findOneConfig/{uuid}', [LandingController::class, 'findOneConfig'])->name('landing.entities.rubrique.findOneConfig');

        Route::get('/services/taxe/findAll/{uuid}', [LandingController::class, 'findAllService'])->name('landing.entities.taxe.find_all');
        Route::post('/services/taxe/payment', [LandingController::class, 'payment'])->name('landing.entities.taxe.payment');
        Route::get('/services/facturation/taxe/info_paiement/{uuid}', [LandingController::class, 'info_paiement'])->name('landing.entities.taxe.info_paiement');
        Route::get('/services/facturation/taxe/data/info_paiement/{uuid}', [LandingController::class, 'paiement_data'])->name('landing.entities.taxe.data.info_paiement');
        Route::get('/services/facturation/taxe/data/generate/file/{uuid}', [LandingController::class, 'generateFile'])->name('landing.entities.taxe.data.generate.file');
        Route::get('/services/facturation/taxe/data/generate/rdv/{uuid}', [LandingController::class, 'generateRdv'])->name('landing.entities.taxe.data.generate.rdv');
        Route::get('/services/facturation/taxe/data/generate/carte/{uuid}', [LandingController::class, 'generateCarte'])->name('landing.entities.taxe.data.generate.carte');
        
        Route::get('/services/facturation/verification-paiement/{ref}', [LandingController::class, 'verificationPaiement'])->name('landing.entities.taxe.facturation.verification.paiement');

    });


    Route::prefix('customer')->middleware(['UserAccess'])->group(function () {
        Route::get('home', [CustomerLoginController::class, 'index'])->name('customer.home');
        Route::get('dashboard', [CustomerLoginController::class, 'index'])->name('customer.dashboard');
       // Route::get('/entite/findAll', [ServicesController::class, 'findAllEntite'])->name('customer.entities.findAll');

        Route::prefix('/services')->group(function(){
            Route::post('/taxe/store', [ServicesController::class, 'store'])->name('customer.entities.taxe.store');
            Route::get('/taxe/update', [ServicesController::class, 'update'])->name('customer.entities.taxe.update');
            Route::get('/taxe/show/{uuid}/{entity_uuid}', [ServicesController::class, 'show'])->name('customer.entities.taxe.show');
            Route::get('/taxe/delete/{uuid}/{entity_uuid}', [ServicesController::class, 'delete'])->name('customer.entities.taxe.delete');
            Route::get('/taxe/find_one/{uuid}/{entity_uuid}', [ServicesController::class, 'find_service'])->name('customer.entities.taxe.find_service');
            
            
            Route::get('/taxe/findAll/{uuid}', [ServicesController::class, 'findAll'])->name('customer.entities.taxe.find_all');
            Route::get('/taxe/{slug}/{target}', [ServicesController::class, 'index'])->name('customer.entities.taxe');

            Route::get('/rubrique/findOneConfig/{uuid}', [ServicesController::class, 'findOneConfig'])->name('customer.entities.rubrique.findOneConfig');

            Route::prefix('facturation')->group(function(){
                Route::post('store', [CustomerFacturationsController::class, 'Paystore'])->name('customer.entities.taxe.facturation.store');
                Route::get('verification-paiement/{ref}', [CustomerFacturationsController::class, 'verificationPaiement'])->name('customer.entities.taxe.facturation.verification.paiement');
                Route::get('verification-validite/{uuid}', [CustomerFacturationsController::class, 'verify_validity'])->name('customer.entities.taxe.verify.validity');

                Route::get('/taxe/info_paiement/{uuid}', [CustomerFacturationsController::class, 'info_paiement'])->name('customer.entities.taxe.info_paiement');
                Route::get('/taxe/data/info_paiement/{uuid}', [CustomerFacturationsController::class, 'paiement_data'])->name('customer.entities.taxe.data.info_paiement');
                Route::get('/taxe/generateFile/{uuid}', [CustomerFacturationsController::class, 'generateFile'])->name('customer.entities.taxe.generate.file');

            });

        });


        
        Route::prefix('securite')->group(function(){
            Route::get('compte', [UsersController::class, 'securite'])->name('customer.securite.compte');
            Route::post('compte/update/data', [UsersController::class, 'updateAccount'])->name('customer.securite.compte.update.data');
            Route::post('compte/update/password', [UsersController::class, 'updatePassword'])->name('customer.securite.compte.update.password');
            Route::post('compte/update/avatar', [UsersController::class, 'uploadAvatar'])->name('customer.securite.compte.update.avatar');
        });

        Route::get('service/comment/payer', [UsersController::class, 'comment_payer'])->name('customer.service.comment.payer');


        
    });


    
    Route::get('controle/login', [ControlesController::class, 'login'])->name('controle.login');
    Route::post('controle/login/submit', [ControlesController::class, 'connexion'])->name('controle.connexion');

    Route::prefix('controle')->middleware(['ControlConnect'])->group(function(){
        Route::get('home', [ControlesController::class, 'index'])->name('controle.home');
        Route::get('verify/{decodedText}', [ControlesController::class, 'verify'])->name('controle.verify');
        Route::get('manual/verify/{decodedText}', [ControlesController::class, 'verify_manual'])->name('controle.verify.manual');

    });

    Route::get('login', [CustomerLoginController::class, 'login'])->name('login');
    Route::post('logout', [CustomerLoginController::class, 'logout'])->name('logout');
    
    Route::post('customer/register/submit', [CustomerLoginController::class, 'register_submit'])->name('customer.register.submit');
    Route::get('register/{service}', [CustomerLoginController::class, 'register'])->name('register');
    Route::get('/quick/payment/{name?}/{service}', [LandingController::class, 'quick_pay'])->name('quick.payment');
    Route::get('/quick/liste/{name?}/{service}', [LandingController::class, 'quick_liste'])->name('quick.liste');
    Route::get('/quick/acquitter/{name?}/{service}', [LandingController::class, 'quick_acquitter'])->name('quick.acquitter');
    Route::get('/nous/contacter/{name?}/{service}', [LandingController::class, 'about'])->name('about');
    Route::post('/send/nous/contacter', [LandingController::class, 'about_send'])->name('about.send');

