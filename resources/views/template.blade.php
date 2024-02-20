<!DOCTYPE html>
<html lang="ru-RU">

@include('blocks.head')

<body x-data="{ menuOverlayIsOpen: false }" :class="menuOverlayIsOpen &amp;&amp; 'no-scroll'">

{!! Settings::get('counters') !!}

@include('blocks.header')

@yield('content')

{{--@include('blocks.footer')--}}

{{--@include('blocks.popups')--}}

{{--<div class="v-hidden" id="company" itemprop="branchOf" itemscope itemtype="https://schema.org/Corporation"--}}
{{--     aria-hidden="true" tabindex="-1">--}}
{{--    {!! Settings::get('schema.org') !!}--}}
{{--</div>--}}

{{--@if(isset($admin_edit_link) && strlen($admin_edit_link))--}}
{{--    <div class="adminedit">--}}
{{--        <div class="adminedit__ico"></div>--}}
{{--        <a href="{{ $admin_edit_link }}" class="adminedit__name" target="_blank">Редактировать</a>--}}
{{--    </div>--}}
{{--@endif--}}

</body>
</html>
