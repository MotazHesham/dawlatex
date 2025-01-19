@if(count($child_category->childrenCategories) > 0)
    <div data-level="{{$child_category->level + 1}}" class="mt-3">
        <select name="category_ids[{{$child_category->level + 1}}]" class="form-control" onchange="getSequanceCategory(this,{{$child_category->level + 2}})" required>
            <option value="">{{ translate('Select Category') }}</option> 
            @foreach ($child_category->childrenCategories as $raw)
                <option value="{{ $raw->id }}" @if(in_array($raw->id,$old_categories)) selected @endif>{{ $raw->getTranslation('name') }}</option> 
            @endforeach
        </select>
    </div> 

    @foreach ($child_category->childrenCategories as $childCategory)
        @if(in_array($childCategory->id,$old_categories))
            @include('backend.product.products.sequance_child_category', ['child_category' => $childCategory, 'old_categories' => $old_categories])
        @endif
    @endforeach 
@endif