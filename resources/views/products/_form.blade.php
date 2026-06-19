<div class="mb-3">
    <label for="title" class="form-label fw-semibold">Product Name <span class="text-danger">*</span></label>
    <input type="text" id="title" name="title"
           value="{{ old('title', $product->title ?? '') }}"
           required minlength="2" maxlength="255"
           placeholder="e.g. Fresh Organic Apples"
           class="form-control @error('title') is-invalid @enderror">
    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label for="category" class="form-label fw-semibold">Category</label>
        <select id="category" name="category" class="form-select @error('category') is-invalid @enderror">
            <option value="">— Select category —</option>
            @foreach(['fruits'=>'🍎 Fruits','vegetables'=>'🥦 Vegetables','dairy'=>'🥛 Dairy','bakery'=>'🍞 Bakery','other'=>'📦 Other'] as $val=>$label)
                <option value="{{ $val }}" {{ old('category', $product->category ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label for="price" class="form-label fw-semibold">Price (USD) <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text">$</span>
            <input type="number" id="price" name="price"
                   value="{{ old('price', isset($product) ? number_format((float)$product->price, 2, '.', '') : '') }}"
                   required min="0" max="9999999.99" step="0.01"
                   class="form-control @error('price') is-invalid @enderror"
                   placeholder="0.00">
        </div>
        @error('price')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label for="stock" class="form-label fw-semibold">Stock Qty</label>
        <input type="number" id="stock" name="stock"
               value="{{ old('stock', $product->stock ?? 0) }}"
               min="0" max="99999"
               class="form-control @error('stock') is-invalid @enderror"
               placeholder="0">
        @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3">
    <label for="description" class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
    <textarea id="description" name="description" rows="4" required minlength="10"
              class="form-control @error('description') is-invalid @enderror"
              placeholder="Describe the product, origin, benefits…">{{ old('description', $product->description ?? '') }}</textarea>
    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label for="image_path" class="form-label fw-semibold">Image Path</label>
        <input type="text" id="image_path" name="image_path"
               value="{{ old('image_path', $product->image_path ?? '') }}"
               class="form-control @error('image_path') is-invalid @enderror"
               placeholder="img/fruite-item-1.jpg">
        <div class="form-text">Relative path from <code>public/</code>, e.g. <code>img/fruite-item-1.jpg</code></div>
        @error('image_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="date_available" class="form-label fw-semibold">Date Available <span class="text-danger">*</span></label>
        <input type="date" id="date_available" name="date_available"
               value="{{ old('date_available', isset($product) ? $product->date_available->format('Y-m-d') : date('Y-m-d')) }}"
               required
               class="form-control @error('date_available') is-invalid @enderror">
        @error('date_available')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

{{-- Image preview --}}
@if(!empty($product->image_path ?? null))
<div class="mb-3">
    <img src="{{ asset($product->image_path) }}" alt="Current image"
         style="height:120px;object-fit:cover;border-radius:.5rem;border:2px solid #e5e7eb">
    <div class="form-text">Current image</div>
</div>
@endif
