@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-lg overflow-hidden shadow-lg">
        <div class="px-6 py-4 bg-blue-600 text-white">
            <h1 class="text-2xl font-bold">Edit Salary</h1>
        </div>

        <div class="p-6">
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Employee Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Name</p>
                        <p class="mt-1">{{ $employee->first_name }} {{ $employee->last_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Employee ID</p>
                        <p class="mt-1">{{ $employee->id }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Department</p>
                        <p class="mt-1">{{ $employee->department }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Position</p>
                        <p class="mt-1">{{ $employee->position }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Current Salary</p>
                        <p class="mt-1">${{ number_format($employee->salary, 2) }}</p>
                    </div>

                </div>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p class="font-bold">Please correct the following errors:</p>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('salaries.update', $employee->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="salary" class="block text-gray-700 text-sm font-bold mb-2">New Salary</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="text" name="salary" id="salary" class="form-input block w-full pl-7 pr-12 sm:text-sm sm:leading-5 border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="0.00" value="{{ old('salary', $employee->salary) }}">
                        <div class="absolute inset-y-0 right-0 flex items-center">
                            <span class="text-gray-500 sm:text-sm mr-2">USD</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300 ease-in-out">
                        Update Salary
                    </button>
                    <a href="{{ route('salaries.index') }}" class="ml-4 inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800 transition duration-300 ease-in-out">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
