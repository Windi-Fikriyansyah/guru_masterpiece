@extends('layouts.admin')

@section('header')
    Dashboard Overview
@endsection

@section('content')


    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Activity -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
                <h3 class="font-bold text-slate-800">Recent Transactions</h3>
                <button class="text-sm font-semibold text-primary hover:underline">View All</button>
            </div>
            <div class="p-4">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider">
                            <th class="px-4 py-3">Customer</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Amount</th>
                            <th class="px-4 py-3">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-600">JD</div>
                                    <span class="text-sm font-semibold text-slate-700">John Doe</span>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-xs font-bold rounded-full border border-emerald-100">Completed</span>
                            </td>
                            <td class="px-4 py-4 text-sm font-bold text-slate-700">$240.00</td>
                            <td class="px-4 py-4 text-sm text-slate-500">Jan 20, 2024</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-600">AS</div>
                                    <span class="text-sm font-semibold text-slate-700">Alice Smith</span>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="px-3 py-1 bg-amber-50 text-amber-600 text-xs font-bold rounded-full border border-amber-100">Pending</span>
                            </td>
                            <td class="px-4 py-4 text-sm font-bold text-slate-700">$540.00</td>
                            <td class="px-4 py-4 text-sm text-slate-500">Jan 19, 2024</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- System Summary -->
        <div class="bg-indigo-600 rounded-3xl p-8 text-white relative overflow-hidden shadow-xl shadow-indigo-200">
            <div class="relative z-10">
                <h3 class="text-xl font-bold mb-2">Upgrade to Pro</h3>
                <p class="text-indigo-100 text-sm mb-6 leading-relaxed">Access advanced features and detailed analytics to grow your business.</p>
                <button class="bg-white text-indigo-600 px-6 py-3 rounded-2xl font-bold text-sm hover:bg-indigo-50 transition-colors shadow-lg">Get Started Now</button>
            </div>
            
            <!-- Abstract background shape -->
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute -left-10 -top-10 w-32 h-32 bg-indigo-400/20 rounded-full blur-2xl"></div>
        </div>
    </div>
@endsection
