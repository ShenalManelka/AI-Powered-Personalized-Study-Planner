<x-app-layout>
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Edit Subject</h1>
            <p class="text-gray-600 mt-1">Update the details of your subject.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <form action="{{ route('subjects.update', $subject) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div>
                    <x-input-label for="code" value="Subject Code" class="text-sm font-semibold text-gray-700" />
                    <x-text-input id="code" name="code" value="{{ $subject->code }}" required class="block w-full mt-2 border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                </div>
                
                <div>
                    <x-input-label for="name" value="Subject Name" class="text-sm font-semibold text-gray-700" />
                    <x-text-input id="name" name="name" value="{{ $subject->name }}" required class="block w-full mt-2 border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                </div>
                
                <div>
                    <x-input-label for="description" value="Description (Optional)" class="text-sm font-semibold text-gray-700" />
                    <textarea id="description" name="description" rows="3" class="block w-full mt-2 rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $subject->description }}</textarea>
                </div>
                
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-100">
                    <a href="{{ route('subjects.index') }}" class="px-4 py-2 border border-gray-300 rounded-xl text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Update Subject
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>