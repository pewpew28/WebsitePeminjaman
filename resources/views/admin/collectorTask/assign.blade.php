<x-admin-layout>
       <div class="container mx-auto px-4 py-8">
           <h1 class="text-2xl font-bold mb-6">Assign Task to {{ $collector->name }}</h1>

           <form action="{{ route('admin.collector-tasks.store-assignment', $collector) }}" method="POST" class="bg-white p-6 rounded-lg shadow">
               @csrf
               <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                   <div>
                       <label for="nasabah_id" class="block text-sm font-medium text-gray-700">Nasabah</label>
                       <select name="nasabah_id" id="nasabah_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                           @foreach($nasabahs as $nasabah)
                               <option value="{{ $nasabah->id }}">{{ $nasabah->name }}</option>
                           @endforeach
                       </select>
                       @error('nasabah_id')
                           <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                       @enderror
                   </div>

                   <div>
                       <label for="loan_id" class="block text-sm font-medium text-gray-700">Loan (Optional)</label>
                       <select name="loan_id" id="loan_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                           <option value="">Select Loan</option>
                           @foreach($loans as $loan)
                               <option value="{{ $loan->id }}">Loan #{{ $loan->id }} - {{ $loan->nasabah->name }}</option>
                           @endforeach
                       </select>
                       @error('loan_id')
                           <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                       @enderror
                   </div>

                   <div>
                       <label for="assigned_date" class="block text-sm font-medium text-gray-700">Assigned Date</label>
                       <input type="date" name="assigned_date" id="assigned_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200" required>
                       @error('assigned_date')
                           <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                       @enderror
                   </div>

                   <div>
                       <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                       <input type="date" name="due_date" id="due_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200" required>
                       @error('due_date')
                           <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                       @enderror
                   </div>

                   <div class="col-span-2">
                       <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                       <textarea name="notes" id="notes" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"></textarea>
                       @error('notes')
                           <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                       @enderror
                   </div>
               </div>

               <div class="mt-6">
                   <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Assign Task</button>
                   <a href="{{ route('admin.collector-tasks.show', $collector) }}" class="ml-4 text-gray-600 hover:text-gray-800">Cancel</a>
               </div>
           </form>
       </div>
   </x-admin-layout>