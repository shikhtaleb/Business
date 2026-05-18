@extends('layouts.admin')

@section('title', 'تعديل: ' . $page->title_ar)
@section('page-title', 'تعديل الصفحة')

@section('content')
@include('admin.pages._builder', ['page' => $page])
@endsection
