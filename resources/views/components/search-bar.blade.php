<form action="{{ route('search.index') }}" method="GET" class="flex items-center w-full md:w-auto">
    <input type="text" name="query" placeholder="Search for products..." class="border rounded-l-md p-2 w-full md:w-64"
        required value="{{ request('query') }}">
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-r-md hover:bg-blue-700">
        Search
    </button>
</form>
