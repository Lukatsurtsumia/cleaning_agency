<x-site-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="text-3xl font-bold">Edit Project</h1>

        @if ($errors->any())
            <div class="mt-6 rounded-md bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('gallery.update', $gallery) }}" enctype="multipart/form-data" class="mt-8 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="title" value="{{ old('title', $gallery->title) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Category</label>
                <select name="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" @selected(old('category', $gallery->category) === $category)>{{ collect(__('site.services.items'))->firstWhere('key', $category)['name'] ?? Str::headline($category) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $gallery->description) }}</textarea>
            </div>

            @if ($gallery->cover_image)
                <img src="{{ asset('storage/'.$gallery->cover_image) }}" class="w-48 rounded-lg">
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700">Replace cover image</label>
                <input type="file" name="cover_image" accept="image/*" class="mt-1 block w-full text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Add more gallery images</label>
                <input type="file" name="images[]" accept="image/*" multiple class="mt-1 block w-full text-sm">
            </div>

            <button type="submit" class="rounded-md bg-teal-600 px-6 py-3 text-sm font-semibold text-white hover:bg-teal-700">
                Update Project
            </button>
        </form>
    </div>
</x-site-layout>
