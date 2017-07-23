<?php namespace App\Http\Controllers;

use App\Repositories\InstagramRepository;
use Illuminate\Http\Request;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @param InstagramRepository $instagram
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, InstagramRepository $instagram)
    {
        $items = []; $errors = []; $user = NULL;
        if ($request->has('search')) {
            try {
                $user = $instagram->getUser($request->get('search'));
                if ($user->is_private) {
                    throw new \Exception('It`s private account! Content is not accessible');
                }
                $items = $instagram->getItems($request->get('search'));
            } catch (\Exception $e) {
                $errors[] = $e->getMessage();
            }
        }

        return view('home', compact('user', 'items'))->withErrors($errors);
    }
}
