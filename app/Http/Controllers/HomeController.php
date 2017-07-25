<?php namespace App\Http\Controllers;

use App\Repositories\InstagramRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Posts;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
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

    /**
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
//      TODO: Add validation
//        Validator::make($request->all(), [
//            'title' => 'required|unique:posts|max:255',
//            'body' => 'required',
//        ])->validate();

        $response = $this->isExists($request);

        if ($response['status'] == 'error') {
            return response($response);
        }

        $request->merge(['user_id' => Auth::user()->id]);
        Posts::create($request->all());

        return response($response);
    }

    /**
     * Check if content exists
     *
     * @param Request $request
     * @return array
     */
    public function isExists(Request $request)
    {
        $status = ['status' => 'success', 'message' => 'No errors'];

        if (Posts::where('insta_id', $request->input('insta_id'))->first()) {
            $status['status'] = 'error';
            $status['message'] = 'Already exists!';
            return $status;
        }

        return $status;
    }

    /**
     * Show gallery
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function gallery()
    {
        $items = Posts::where('user_id', Auth::user()->id)->get();
        return view('gallery', compact('items'));
    }

    /**
     * Remove post
     *
     * @param Request $request
     * @return array
     */
    public function delete(Request $request)
    {
        Posts::destroy($request->id);
        return ['status' => 'success', 'message' => 'No errors'];
    }
}
