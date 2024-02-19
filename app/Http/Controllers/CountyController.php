<?php

namespace App\Http\Controllers;

use App\Models\County;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isNull;

/**
 * @apiDefine CountyNotFoundError
 *
 * @apiError CountyNotFound Nincs megye ilyen id/név alatt.
 *
 * @apiErrorExample Error-Response:
 *  HTTP/1.1 404 Not Found
 *  {
 *       "data": [],
 *       "message": "CountyNotFound"
 *  }
 */

/**
 * @api {get} /counties Get all counties
 * @apiName all
 * @apiGroup Counties
 *
 * @apiSuccess {Object[]} counties List of counties
 * @apiSuccess {String} counties.name Name of county
 * @apiSuccess {String} counties.flag Flag of county
 * @apiSuccess {String} counties.coat_of_arms The county's coat of arms
 * @apiSuccess {Number} counties.population The county's population
 * @apiSuccess {String} counties.chief_town The county's capital
 *
 * @apiSuccessExample Success-Response:
 * HTTP/1.1 200 OK
 * {
 *      "data":[...
 *              {"id": 18,
 *               "name": "Veszprém",
 *               "flag": "Veszprém_flag.svg",
 *               "coat_of_arms": "Veszprém_coa.svg",
 *               "population": 120000,
 *               "chief_town": Csörög},
 *              {"id": 19,
 *              "name": "Zala",
 *              "flag": "Zala_flag.svg",
 *              "coat_of_arms": "Zala_coa.svg",
 *              "population": 100000,
 *              "chief_town": Vác}],
 *      "message": "Sikeres lekérés"
 * }
 *
 * @apiUse CountyNotFoundError
 */
 /**
 * @api {get} /counties/:id Get one county
 * @apiName GetByIdOrName
 * @apiGroup Counties
 *
 * @apiParam {Number} id County's unique ID
 *
 * @apiSuccessExample Success-Response:
 *  HTTP/1.1 200 OK
 *  {
 *       "data":["id": 19,
 *               "name": "Zala",
 *               "flag": "Zala_flag.svg",
 *               "coat_of_arms": "Zala_coa.svg",
 *               "population": 100000,
 *               "chief_town": Vác],
 *       "message": "Sikeres lekérés"
 *  }
 *
 * @apiErrorExample Error-Response:
 *  HTTP/1.1 404 Not Found
 *  {
 *       "data": [],
 *       "message": "Nincs megye ilyen id alatt"
 *  }
 */
class CountyController extends Controller
{
    public function all(){
        $counties = County::get();
        if($counties->isEmpty()){
            return response(json_encode([
                'data' => [],
                'message' => "Az adatbázis üres",
            ]), Response::HTTP_OK);
        }
        return response(json_encode([
            'data' => $counties,
            'message' => "Sikeres lekérés",
        ]), Response::HTTP_OK);
    }
    public function getByIdOrName($id){
        if(!is_numeric($id)){
            $idByCountyName = County::select('id')->where('name', '=', $id)->first();
            if(!$idByCountyName){
                return response(json_encode([
                    'data' => [],
                    'message' => 'Megye nem található ilyen néven',
                ]), Response::HTTP_NOT_FOUND);
            } else {
                return redirect()->route('CountyGetByIdOrName', ['id' => $idByCountyName]);
            }
        }
        $county = County::where('id', '=', $id)->first();
        if(!$county){
            return response(json_encode([
                'data' => [],
                'message' => 'Megye nem található ilyen id alatt',
            ]), Response::HTTP_NOT_FOUND);
        }
        return response(json_encode([
            'data' => $county,
            'message' => 'Sikeres lekérdezés',
        ]), Response::HTTP_OK);
    }
    public function insert(Request $request){
        County::create([
            'name' => $request->get('name'),
            'flag' => $request->get('name') . "_flag",
            'coat_of_arms' => $request->get('name') . "_coa",
            'population' => $request->get('population'),
            'chief_town' => $request->get('chief_town'),
        ]);

        return response(json_encode([
            'data' => County::where('id', '=', County::count()-1)->get(),
            'message' => 'Megye sikeresen hozzáadva',
        ]), Response::HTTP_CREATED);
    }
    public function delete($id){
        $county = County::find($id);

        if($county->isEmpty()){
            return response(json_encode([
                'data' => [],
                'message' => 'Nincs ilyen megye',
            ]), Response::HTTP_NOT_FOUND);
        }
        $county->delete();
        return response(json_encode([
            'data' => [],
            'message' => 'Megye sikeresen törölve',
        ]), Response::HTTP_OK);
    }
    public function update(Request $request){
        County::update([
            'name' => $request->get('name'),
            'flag' => $request->get('name') . "_flag",
            'coat_of_arms' => $request->get('name') . "_coa",
            'population' => $request->get('population'),
            'chief_town' => $request->get('chief_town'),
        ]);

        return response(json_encode([
            'data' => County::find($request->get('id')),
            'message' => "Megye sikeresen frissítve",
        ]),
            Response::HTTP_OK);
    }
}
