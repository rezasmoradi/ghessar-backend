<?php

$styles = [
    'center' => 'text-align: center;
        width: 500px;
        margin: auto;
        background-color: #efefef;
        border: 1px solid #cccccc;',
    'image' => 'width: 500px;
        height: 800px;',
    'font' => 'font-family: IRANSans, sans-serif'
];
?>

<div style="{{ $styles['center'] }}">
    <h1 style="{{ $styles['font'] }}">سلام به شما کاربر گرامی برنامه قصار</h1>
    <p style="{{ $styles['font'] }}"> کد تایید شما برای ورود به برنامه:</p>
    <p style="{{ $styles['font'] }}">{{$code}}</p>
</div>
