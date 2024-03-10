<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    //index
    public function index()
    {

        $categories = Category::paginate(10);
        return view('pages.categories.index', compact('categories'));
    }

    //create
    public function create()
    {
        return view('pages.categories.create');
    }

    //edit
    //edit
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::all(); // Fetch all categories for the dropdown or other purposes

        return view('pages.categories.edit', compact('category', 'categories'));
    }


    //delete
    public function destroy($id)
    {
        //delete the request..
        $category = category::find($id);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'User deleted successfully');
    }

    //show
    public function show($id)
    {
        return view('pages.categories.show');
    }

    //store
    public function store(Request $request)
    {

        //validate request
        $request->validate([
            'name' => 'required',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048'
        ]);

        //store req..
        $category = new Category();
        $category->name = $request->name;
        $category->description = $request->description;

        // Save image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/categories', $category->id . '.' . $image->getClientOriginalExtension());
            $category->image = 'storage/categories/' . $category->id . '.' . $image->getClientOriginalExtension();
            $category->save();
        }

        return redirect()->route('categories.index')->with('success', 'Category created successfully');
    }

    //update
    public function update(Request $request, $id)
    {
        //validate request
        $request->validate([
            'name' => 'required',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048'

        ]);

        // update req..
        $category = Category::find($id);
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();

        // Save image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/categories', $category->id . '.' . $image->getClientOriginalExtension());
            $category->image = 'storage/categories/' . $category->id . '.' . $image->getClientOriginalExtension();
            $category->save();
        }

        return redirect()->route('categories.index')->with('success', 'Category updated successfully');
    }
}
