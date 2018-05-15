<?php namespace App\Http\Controllers;

use App\Posts;
use Illuminate\Http\Request;
use InstagramScraper\Instagram;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use InstagramScraper\Exception\{InstagramNotFoundException, InstagramException};
use Exception;

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
     * @param Instagram $instagram
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, Instagram $instagram)
    {
        $items = []; $errors = []; $user = NULL;
        if ($request->has('search')) {
            try {
                $user = $instagram->getAccount($request->input('search'));
                $items = $instagram->getMedias($request->input('search'));
            } catch (InstagramNotFoundException $e) {
                $errors[] = $e->getMessage();
            } catch (InstagramException $e) {
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
        $status = ['status' => 'success', 'message' => 'No errors'];

        try {
            $this->isValid($request);
            $this->isExists($request->input('insta_id'));

            $request->merge(['user_id' => Auth::user()->id]);
            Posts::create($request->all());

        } catch (Exception $e) {
            $status['status'] = 'error';
            $status['message'] = $e->getMessage();
        }

        return response()->json($status);
    }

    /**
     * Validate request
     *
     * @param Request $request
     * @throws Exception
     */
    protected function isValid(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'insta_id'      => 'required|numeric|min:1',
            'insta_url'     => 'required|active_url|max:500',
            'insta_caption' => 'required|string|max:500',
            'insta_user'    => 'required|string|max:255',
            'insta_name'    => 'required|string|max:255',
            'insta_time'    => 'required|numeric|min:1000000000',
            'insta_type'    => 'required|string|max:100'
        ]);

        if ($validator->fails()) {
            throw new Exception($validator->errors()->first());
        }
    }

    /**
     * Check if content exists
     *
     * @param string $insta_id
     * @throws Exception
     */
    protected function isExists(string $insta_id)
    {
        if (Posts::where('insta_id', $insta_id)->first()) {
            throw new Exception('Already exists!');
        }
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
