require('../style/style.scss');

import Vue from 'vue';
import Moment from 'moment';
import Axios from 'axios';
import delay from './delay';

Vue.component('menu-item', {
    props: ['item'],
    template: '<div class="icon" @click="trigger(item)">' +
        '<svg width="22" height="23" viewBox="0 0 22 23"><use :xlink:href="item.icon"/></svg>' +
        '<div class="tip">{{ item.tip}}</div>' +
        '</div>'
    ,
    methods: {
        trigger(item) {
            menu.$emit('menuClick', item);
        }
    }
});

var menu = new Vue({
    el: "#menu",
    data: {
        menu: [
            {
                action: "download",
                icon: "#download",
                tip: "Trigger download"
            },
            {
                action: "cleanup",
                icon: "#cleanup",
                tip: "Clean finished"
            },
            {
                action: "link",
                icon: "#web",
                tip: "Open web interface",
                link: "http://iben.space:9091"
            }
        ]
    }
});

Vue.component('episode-group', {
    props: ['group'],
    template: `
        <div class="group">
            <p class="date">{{ formatDate(group.date) }}</p>
            <ul class="dls">
                <episode v-for="episode in group.episodes" v-bind:episode="episode"></episode>
            </ul>
        </div>
        `,
    methods: {
        formatDate: function(dateString) {
            return dateString;//Moment(dateString, 'YYYY/MM/DD').format('MMMM D');
        }
    }
});

Vue.component('episode', {
    props: ['episode'],
    template: `
        <li :class="{new: episode.fresh}">
            {{ episode.title }}
        </li>
        `
});

Vue.component('feed', {
    props: ['feed'],
    template: '<li><a :href="feed.url" target="_blank">{{ feed.url }}</a></li>'
});

var body = new Vue({
    el: ".content",
    data: {
        episodes: [],
        groupedEpisodes: [],
        feeds: [],
        tab: "episodes",
        notify: false,
        notifUpdated: false,
        message: ''
    },
    mounted() {
        this.getEpisodes();
        this.getFeeds();
        menu.$on('menuClick', menuItem => {
            if (menuItem.action == 'link') {
                return window.open(menuItem.link);
            }
            body[menuItem.action]();
        });
    },
    computed: {
        episodesTitle: function() {
            return this.episodes.length > 0 ? "Recent episodes" : "No episodes at all :(";
        }
    },
    methods: {
        getEpisodes: function() {
            this.message = "Updating episodes...";
            this.notify = true;
            Axios.get('api/episodes').then( (response) => {
                delay(1000).wait.then( () => {
                    this.episodes =response.data;
                    this.groupEpisodes();
                    this.notify = false;
                });
            }).catch( () => {
                delay(1000).wait.then( () => {
                    this.message = "Could not update episodes.";
                    this.notifyTimeout();
                });
            });
        },
        download: function() {
            this.message = 'Fetching new episodes...';
            this.notify = true;
            let url = 'api/download';
            let vue = this;
            Axios.get(url).then(response => {
                this.addEpisodes(response.data);
            }).catch(function(e) {
                console.log(e);
            });
        },
        addEpisodes: function(episodes) {
            if ( episodes.length > 0 ) {
                console.log(episodes);
                for (let episode of episodes) {
                    episode.fresh = true;
                    this.episodes.push(episode);
                }
                this.groupEpisodes();
                this.notify = false;
            } else {
                this.message = "No new episodes this time.";
                this.notifyTimeout();
            }
        },
        getFeeds: function() {
            var vue = this;
            var url = 'api/feeds';
            Axios.get(url).then(function(response){
                vue.feeds = response.data;
            });
        },
        switchTab: function(tab) {
            this.tab = tab;
        },
        cleanup: function() {
            var vue = this;
            this.message = 'Cleaning up torrents...';
            this.notify = true;
            Axios.get('api/cleanup').then( response => {
                let removed = response.data;
                if (removed.length > 0) {
                    this.message = 'Removed ' + removed.length + 'torrent(s).';
                } else {
                    this.message = "Nothing to remove.";
                }
            });
            this.notifyTimeout();
        },
        groupEpisodes: function() {
            let reducedEpisodes = this.episodes.reduce( (reduced, episode) => {
                let date = Moment(episode.created_at).format('YYYY/MM/DD');
                /*if (!reduced[date]) {
                    reduced[date] = [];
                }
                reduced[date].push(episodes);*/
                (reduced[date] = reduced[date] || []).push(episode);
                return reduced;
            }, {});
            let groupedEpisodes = [];
            for (let date in reducedEpisodes) {
                groupedEpisodes.push({
                    date: date,
                    episodes: reducedEpisodes[date]
                });
            };
            this.groupedEpisodes = groupedEpisodes.sort().reverse();
        },
        notifyTimeout: function() {
            if (this.d) {
                this.d.drop();
            }
            this.d = delay(2000);
            this.d.wait.then( () => {
                this.notify = false;
            }).catch( () => {
                console.log('dropped');
            });
        },
        updateNotification: function(message) {
            this.notifUpdated = true;
            this.message = message;
            delay(1000).wait.then( () => {
                this.notifUpdated = false;
            });
        }
    }
});