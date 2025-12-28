{{-- resources/views/p3k/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit P3K ' . ($p3k->serial_no ?? ''))

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6 space-y-6">
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-600 via-green-600 to-emerald-700 p-8 shadow-xl">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
        <div class="relative flex items-start justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-1