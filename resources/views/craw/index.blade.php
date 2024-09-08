<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Craw Data') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <form action="" method="post" class="w-full mx-auto p-5">
                    @csrf
                    <div class="mb-12">
                        <label for="urls" class="block text-gray-600 font-semibold mb-2">Enter URLs (one per line)</label>
                        <input name="urls" class="w-full p-2 border rounded focus:outline-none focus:border-blue-400"/>
                    </div>
                    <div class="mb-12">
                        <label for="categories" class="block text-gray-600 font-semibold mb-2">Select Categories</label>
                        <select name="categories[]" id="categories" multiple class="w-full p-2 border rounded focus:outline-none focus:border-blue-400">
                            <option value="category1">Category 1</option>
                            <option value="category2">Category 2</option>
                            <option value="category3">Category 3</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
