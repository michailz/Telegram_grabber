<?php if (!defined("SYSTEM")) die('Error 404');?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$title;?></title>
    <script defer src="/js/all.js"></script>
    <script type="module" src="/js/ionicons.esm.js"></script>
    <script nomodule="" src="/js/ionicons.js"></script>
    <script>
    window.addEventListener("load", function(){
    // (C2) POPUP DATE PICKER
      picker.attach({
        target: "input-pop"
      });
      picker.attach({
        target: "input-pop2"
      });
    });
    </script>
    <link href="/css/dp-light.css" rel="stylesheet">
    <script src="/js/datepicker.js"></script>
    <link rel="stylesheet" href="/css/bulma.min.css">
    <style>
    .message:not(:last-child) {margin-bottom: .2em;}
    .table td, .table th {padding: .3em .5em;}
    .nowrap {
      white-space: nowrap;
    }
    .wrap {
      overflow-wrap: break-word;
      word-break: break-all;
      hyphens: auto;
    }
    audio {
    width: 300px;
    height: 34px;
    }
    </style>

  </head>
  <body>
