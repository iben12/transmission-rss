<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>TransmissionRSS</title>
    <meta name="description" content="content">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Place favicon.ico and apple-touch-icon(s) in the root directory -->
    <link rel="stylesheet" href="assets/style.css">


</head>
<body>
<header>
    <div id="logo">
        <img src="images/logo.svg" alt="TransmissionRSS">
    </div>
    <div id="title">
        <div class="text">
            <h1><span class="red bold">T</span>ransmission<span class="bold">RSS</span></h1>
            <span class="subtitle">RSS Download Tool</span>
        </div>
    </div>
    <div id="menu">
        <menu-item v-for="item in menu" v-bind:item="item"></menu-item>
    </div>
</header>
<div class="content" v-cloak>
    <div id="switch">
        <span class="dl"
              :class="{selected: tab == 'episodes'}"
              @click="switchTab('episodes')">Downloads</span><span class="fd"
                   :class="{selected: tab == 'feeds'}"
                   @click="switchTab('feeds')">Feeds</span>
    </div>
    <transition name="slide-fade">
        <div id="notif"v-if="notify"><p v-html=message :class="{flash: notifUpdated}"></p></div>
    </transition>
    <transition name="just-fade" mode="out-in">
        <div class="dl-tab" v-if="tab == 'episodes'" key="dl">
            <h2>Recent downloads</h2>
            <episode-group v-for="group in groupedEpisodes" v-bind:group="group"></episode-group>
        </div>

        <div class="feeds-tab" v-else key="feed">
            <h2>Feeds</h2>
            <ul class="feeds">
                <feed v-for="feed in feeds" v-bind:feed="feed"></feed>
            </ul>
        </div>
    </transition>
</div>
<footer>
    <p class="small">&copy; TransmissionRSS</p>
</footer>
</body>
<?php
    require 'Partials/icons.php';
?>

<script type="text/javascript" src="assets/app.js"></script>
</html>

<?php return;
