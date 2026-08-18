<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HelpArticle;
use App\Models\HelpCategory;
use Illuminate\Http\Request;

class HelpArticleController extends Controller
{
    public function index()
    {
        $articles = HelpArticle::with('category')->orderBy('id', 'desc')->get();
        return view('admin.help_articles.index', compact('articles'));
    }

    public function create()
    {
        $categories = HelpCategory::orderBy('sort_order')->get();
        return view('admin.help_articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'help_category_id' => 'required|exists:help_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:help_articles',
            'content' => 'required|string',
            'is_published' => 'boolean',
        ]);

        HelpArticle::create([
            'help_category_id' => $request->help_category_id,
            'title' => $request->title,
            'slug' => $request->slug,
            'content' => $request->content,
            'is_published' => $request->has('is_published') ? $request->is_published : true,
        ]);

        return redirect()->route('admin.help_articles.index')->with('success', 'Artikel Bantuan berhasil ditambahkan.');
    }

    public function edit(HelpArticle $helpArticle)
    {
        $categories = HelpCategory::orderBy('sort_order')->get();
        return view('admin.help_articles.edit', compact('helpArticle', 'categories'));
    }

    public function update(Request $request, HelpArticle $helpArticle)
    {
        $request->validate([
            'help_category_id' => 'required|exists:help_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:help_articles,slug,' . $helpArticle->id,
            'content' => 'required|string',
            'is_published' => 'boolean',
        ]);

        $helpArticle->update([
            'help_category_id' => $request->help_category_id,
            'title' => $request->title,
            'slug' => $request->slug,
            'content' => $request->content,
            'is_published' => $request->has('is_published') ? $request->is_published : true,
        ]);

        return redirect()->route('admin.help_articles.index')->with('success', 'Artikel Bantuan berhasil diperbarui.');
    }

    public function destroy(HelpArticle $helpArticle)
    {
        $helpArticle->delete();
        return redirect()->route('admin.help_articles.index')->with('success', 'Artikel Bantuan berhasil dihapus.');
    }
}
