// TODO: use requestAnimationFrame?

var slideshow = {

    init: function(id, effect, delay, pause, duration) {
        if (window.addEventListener) {
            window.addEventListener('load', function() {
                slideshow.play(id, effect, delay, pause, duration);
            }, false);
        } else {
            window.attachEvent('onload', function() {
                slideshow.play(id, effect, delay, pause, duration);
            });
        }
    },

    play: function(id, effect, delay, pause, duration) {
        var start, imgs, frame;

        imgs = document.getElementById(id).childNodes;
        window.setTimeout(function() {
            start = new Date().getTime();
            window.setInterval(frame, 20);
        }, delay);
        frame = function() {
            var elapsed = (new Date().getTime() - start) / (pause + duration);
            var slide = Math.floor(elapsed);
            var pauseRatio = pause / (pause + duration);
            var n = slide % imgs.length;
            var m = (slide + 1) % imgs.length;
            if (elapsed - slide - pauseRatio < 0) {
                slideshow[effect + "End"](imgs[n], imgs[m]);
                return;
            }
            var effectRatio = duration / (pause + duration);
            var progress = Math.max(elapsed - slide - pauseRatio, 0) / effectRatio;
            //progress *= progress * progress;
            for (var i = 0; i < imgs.length; i++) {
                if (i != n && i != m) {
                    imgs[i].style.display = "none";
                    imgs[i].style.zIndex = 0;
                }
            }
            slideshow[effect](imgs[n], imgs[m], progress);
        }
    },

    test: function() {console.log(1)},


    fade: function(img1, img2, progress) {
        slideshow.setOpacity(img1, 1);
        img1.style.zIndex = 1;
        img1.style.display = "block";
        slideshow.setOpacity(img2, progress);
        img2.style.zIndex = 2;
        img2.style.display = "block";
    },


    fadeEnd: function(img1, img2) {
        slideshow.setOpacity(img1, 1);
        img2.style.display = "none";
    },


    slide: function(img1, img2, progress) {
        img1.style.left = (img1.width * progress) + "px";
        img1.style.zIndex = 1;
        img1.style.display = "block";
        img2.style.left = (img1.width * progress - img2.width) + "px";
        img2.style.zIndex = 2;
        img2.style.display = "block";
    },


    slideEnd: function(img1, img2) {
        img1.style.left = 0;
        img2.style.display = "none";
    },


    curtain: function(img1, img2, progress) {
        img1.style.zIndex = 1;
        img1.style.display = "block";
        img2.style.top = (img1.height * progress - img1.height) + "px";
        img2.style.zIndex = 2;
        img2.style.display = "block";
    },


    curtainEnd: function(img1, img2) {
        img1.style.top = 0;
        img2.style.display = "none";
    },


    setOpacity: function(img, val) {
        if (typeof img.style.opacity != 'undefined') {
            img.style.opacity = val;
        } else {
            img.style.filter = "alpha(opacity = " + Math.round(100 * val) + ")";
        }
    }

}
