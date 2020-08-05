<?php
namespace App\Http\Traits;

use App\Category;

trait ItemsTrait{
    /**
     * @param $item_id
     * @param int $category_id
     * @return array|string
     */
    public function getCategoriesOfItem($item_id, $category_id = 0){
        $categories = Category::query();
        $categories = $categories->select('categories.id', 'categories.name');
        $categories = $categories->join('item_to_categories', 'item_to_categories.category_id', '=', 'categories.id');
        $categories = $categories->where('item_to_categories.item_id', $item_id);

        if($category_id)
            $categories = $categories->where('categories.id', '!=', $category_id);

        $categories = $categories->orderBy('categories.name', 'asc');
        $categories = $categories->get();
        $category_html = '';
        if($categories->isNotEmpty()){
            $count = 1;
            foreach ($categories as $category){
                $category_html .= '<a href="/category/' . $category->id . ' ">' . $category->name . '</a>';
                if($count < $categories->count()){
                    $category_html .= ', ';
                }
                $count++;
            }
        }

        return $category_html;
    }
}
