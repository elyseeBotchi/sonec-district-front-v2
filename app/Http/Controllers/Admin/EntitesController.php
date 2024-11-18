<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GlobalSendService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EntitesController extends Controller
{

    public function index(Request $request)
    {

        return view('admins.configurations.entites.index');

    }

    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'description' => 'string|nullable',
            'columns' => 'array|nullable', // 
        ]);

        $url_path = "/autorisations/entite/store";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'name' => htmlspecialchars($validatedData['name'], ENT_QUOTES, 'UTF-8'),
            'front_name' => htmlspecialchars($request->front_name, ENT_QUOTES, 'UTF-8'),
            'description' => htmlspecialchars($validatedData['description'], ENT_QUOTES, 'UTF-8'),
            'columns' => $validatedData['columns'], // Si c'est du JSON ou un tableau
        ];


       // return dd( $validatedData['columns']);
        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'POST');

      //  return dd($dataResponse);

        if($dataResponse['type'] == 'error'){
            $dataResponse =[
                'type'=>'error',
                'urlback'=>'',
                'message'=>$dataResponse['message'] ?? '',
                'code'=>500,
            ];
            
            return response()->json($dataResponse);
        }
        else{
            $uuid = $dataResponse['data']['uuid'] ?? '';
            return response()->json([
                'type' => 'success',
                'message' => "Un élément enregistré",
                'code' => 200,
                'urlback'=> route('panel.autorisations.entite.show', ['uuid' => $uuid])
            ]);
        }
    }

        
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'uuid' => 'required',
            'name' => 'string|max:255',
            'description' => 'string|nullable',
        ]);

        $url_path = "/autorisations/entite/update";

        $data = [
            'uuid' => $request->uuid,
            'name' => htmlspecialchars($validatedData['name'], ENT_QUOTES, 'UTF-8'),
            'front_name' => htmlspecialchars($request->front_name, ENT_QUOTES, 'UTF-8'),
            'description' => htmlspecialchars($validatedData['description'], ENT_QUOTES, 'UTF-8'),
        ];


       // return dd( $validatedData['columns']);
        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'POST');

       // return dd($dataResponse);

       return response()->json($dataResponse);
       
    }

    public function findAll(Request $request)
    {
        $url_path = "/autorisations/entite/findAll";

        $data = [
            'admin_uuid' => AuthConnect()['uuid']
        ];

        $entitiesList = (new GlobalSendService())->CallApi($url_path,$data,'GET');

      //  return dd($entitiesList);
        return response()->json($entitiesList);
    }

    public function findOneConfig($uuid)
    {
        $url_path = "/autorisations/entite/findOneConfig";

        $data = [
          //  'admin_uuid' => AuthConnect()['uuid'],
            'uuid' => $uuid 
        ];

      
        $entityElement = (new GlobalSendService())->CallApi($url_path,$data,'POST');
        // return dd($entityElement);

        return response()->json($entityElement);
    }

    public function show($uuid)
    {
        return view('admins.configurations.entites.show', [
            'entity_uuid' => $uuid
        ]);
    }


    public function lock(Request $request)
    {
        $url_path = "/autorisations/admins/delete";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'uuid'=>$request->id ?? '',
            'status'=>$request->param ?? ''
        ];

        $response = (new GlobalSendService())->CallApi($url_path,$data,'POST');
        //return dd($collaborators);
        if(isset($response['type'])){
            if($response['type'] =='success'){
                $dataResponse =[
                    'type'=>'success',
                    'urlback'=>route('panel.autorisations.entite.show',['uuid'=>$response['data']['uuid']]),
                    'message'=>$collaborators['message'] ?? '',
                    'code'=>200,
                ];
            return response()->json($dataResponse);
            }
            else{
                $dataResponse =[
                    'type'=>'error',
                    'urlback'=>'',
                    'message'=>$collaborators['message'] ?? '',
                    'code'=>500,
                ];
                return response()->json($dataResponse);
            }
        }else{
            $dataResponse =[
                'type'=>'error',
                'urlback'=>'',
                'message'=>$collaborators['message'] ?? '',
                'code'=>500,
            ];
            return response()->json($dataResponse);
        }

    }

    public function rubrique_sotre(Request $request){

        $url_path = "/autorisations/entite/rubrique/store";

        $data = [
            //'admin_uuid' => AuthConnect()['uuid'],
            'rubrique' =>$request->rubrique,
        ];

        $entitiesList = (new GlobalSendService())->CallApi($url_path,$data,'GET');

      //  return dd($entitiesList);
        return response()->json($entitiesList);
    }

    
    public function gabari($uuid)
    {
       
        return view('admins.configurations.entites.gabari', [
            'Entity_uuid' => $uuid
        ]);
    }

    public function gabariFindAll($uuid){
        $url_path = "/autorisations/entite/gabari/findAll";

        $data = [
            'entity_uuid' => $uuid 
        ];
      
        $entityElement = (new GlobalSendService())->CallApi($url_path,$data,'POST');

        return response()->json($entityElement);
    }

    public function gabariStore(Request $request){  
        
        $validatedData = $request->validate([
            'entity_uuid' => 'required|string',
            'gabari' => 'required|file|mimes:xls,xlsx|max:20048', // Ajout des extensions Excel (.xls, .xlsx)
            //'description' => 'nullable|string|max:500',
        ]);

      
        if ($request->hasFile('gabari')) {
            $file = $request->file('gabari');
            
            $newFileName = uniqid() . '_' . $file->getClientOriginalName();  // Ou $file->getClientOriginalExtension() si vous voulez juste garder l'extension
            
            $filePath = $file->storeAs('gabarits', $newFileName, 'public');
        }
       
        if ($request->hasFile('gabari')) {
            // Récupérer le fichier
            $file = $request->file('gabari');
            
            // Générer un nom unique pour le fichier
            $newFileName = uniqid() . '_' . $file->getClientOriginalName();
            
            // Sauvegarder le fichier dans le dossier "gabarits" sous 'public'
            $filePath = $file->storeAs('gabarits', $newFileName, 'public');
            
            // Charger le fichier Excel avec PhpSpreadsheet
            $path = storage_path('app/public/' . $filePath);
            $spreadsheet = IOFactory::load($path);
            
            // Vérifier si la feuille "gabarie" existe
            $sheetNames = $spreadsheet->getSheetNames();
            
            if (!in_array('gabarie', $sheetNames)) {
                $dataResponse =[
                    'type'=>'error',
                    'urlback'=>'',
                    'message'=> "La feuille gabarie n'a pas été trouvée dans le fichier Excel.",
                    'code'=>500,
                ];

                return response()->json($dataResponse);

            }
        
            // Sélectionner la feuille "gabarie"
            $worksheet = $spreadsheet->getSheetByName('gabarie');
            
            // Boucle pour lire toutes les lignes et colonnes
            $dataSheet = [];
            foreach ($worksheet->getRowIterator() as $row) {
                $rowIndex = $row->getRowIndex();
                $rowData = [];
                
                foreach ($worksheet->getColumnIterator() as $column) {
                    $colIndex = $column->getColumnIndex();
                    $cellValue = $worksheet->getCell($colIndex . $rowIndex)->getValue();
                    $rowData[$colIndex] = $cellValue;
                }
        
                // Ajouter la ligne au tableau des données
                $dataSheet[] = $rowData;
            }
        
            // Vous pouvez maintenant manipuler les données extraites dans $data
          //  dd($dataSheet);  // Pour voir le contenu des données
        }
        
    
        $url_path = "/autorisations/entite/gabari/store";
        //$url_path = "/autorisations/entite/gabari/findAll";

        // Créer une instance de Multipart pour envoyer le fichier
        $data = [
            'entity_uuid' => $request->entity_uuid,
            'description' => $request->description,
           // 'gabari' => curl_file_create($request->file('gabari')->getPathname(), $request->file('gabari')->getMimeType(), $request->file('gabari')->getClientOriginalName()),
            'filename' => $newFileName ?? '',
            'data_sheet' => $dataSheet ?? [],
        ];
        
        // Utiliser cURL ou une bibliothèque d'API qui gère multipart/form-data
        $data_response = (new GlobalSendService())->CallApi($url_path, $data, 'POST');  // Ajoutez un paramètre supplémentaire pour indiquer multipart/form-data si nécessaire
        
        //return dd($data_response);
        return response()->json($data_response);
    }

    
    public function validateFile($uuid,$status)
    {
        $url_path = "/autorisations/entite/gabari/validate";

        $data = [
            'gabari_uuid' => $uuid,
            'status' => $status,
        ];
        
        $data_response = (new GlobalSendService())->CallApi($url_path, $data, 'POST');  // Ajoutez un paramètre supplémentaire pour indiquer multipart/form-data si nécessaire
        
       // return dd($data_response);
        return response()->json($data_response);
    }
    
    public function gabari_model($uuid){
        $url_path = "/autorisations/entite/findOneConfig";

        $data = [
            'uuid' => $uuid 
        ];

        $entityElement = (new GlobalSendService())->CallApi($url_path,$data,'POST');
    
        $entity_attributes = isset($entityElement['data']['entity_attribute'][0]['config_schema']) ? $entityElement['data']['entity_attribute'][0]['config_schema'] : '';
       // return dd($entityElement['data']['entity_attribute'][0]['config_schema']);

       // return response()->json($entity_attributes);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('gabarie');
        
        $ExceAattribute = [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
            'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ',
            'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ',
            'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ',
            'DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ',
            'EA', 'EB', 'EC', 'ED', 'EE', 'EF', 'EG', 'EH', 'EI', 'EJ', 'EK', 'EL', 'EM', 'EN', 'EO', 'EP', 'EQ', 'ER', 'ES', 'ET', 'EU', 'EV', 'EW', 'EX', 'EY', 'EZ'
        ];


      // return dd($entity_attributes);
        // Remplir les colonnes avec les attributs
     

            $entity_attributes_lines = json_decode($entity_attributes,true);

            foreach ($entity_attributes_lines as $key => $attribute) {  //dd($attribute['slug']);
                $sheet->setCellValue($ExceAattribute[$key] . 1, $attribute['slug']);
            }
        

        // Générer des données d'exemple en fonction des colonnes définies dans $entity_attributes
        for ($i = 1; $i <= 10; $i++) { 
            foreach (json_decode($entity_attributes,true) as $key => $attribute) {
                switch ($attribute['slug']) {
                    case 'nom_du_proprietaire':
                        $sheet->setCellValue($ExceAattribute[$key] . ($i + 1), 'Nom ' . $i);
                        break;
                    case 'numero_de_la_carte_grise':
                        $sheet->setCellValue($ExceAattribute[$key] . ($i + 1), 'CG-' . str_pad($i, 8, '0', STR_PAD_LEFT));
                        break;
                    case 'numero_dimmatriculation':
                        $sheet->setCellValue($ExceAattribute[$key] . ($i + 1), 'IM-' . str_pad($i, 6, '0', STR_PAD_LEFT));
                        break;
                    case 'telephone':
                        $sheet->setCellValue($ExceAattribute[$key] . ($i + 1), '07' . rand(10000000, 99999999));
                        break;
                    case 'email':
                        $sheet->setCellValue($ExceAattribute[$key] . ($i + 1), 'email' . $i . '@example.com');
                        break;
                    // Ajoutez des cas supplémentaires ici pour d'autres attributs
                }
            }
        }

        // Télécharger directement le fichier Excel
        $writer = new Xlsx($spreadsheet);

        // Utilisation de StreamedResponse pour retourner le fichier en téléchargement
        return new StreamedResponse(function() use ($writer) {
            $writer->save('php://output'); // Écrit directement dans la sortie
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment;filename="gabari.xlsx"',
            'Cache-Control' => 'max-age=0',
        ]);

    }
}
