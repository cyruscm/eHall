var $rleg = $("#leg2"),
  $lleg = $("#leg1"),
  $shadow = $("#shadow"),
  $panda = $(".p-body"),
  $features = $("#features"),
  $t1 = $("h2"),
  $lasers = $("#lasers line");

TweenMax.set($lasers, {
  rotation: 150,
  x: -15,
  opacity: 0,
  drawSVG: '0 0',
  visibility: "visible"
});

TweenMax.set($t1, {
  perspective: 400
});

// the first scene
function sceneOne() {
  var tl = new TimelineMax();

  tl.add("start");
  tl.staggerTo([$rleg, $lleg], 0.5, {
      scaleY: 0.96,
      transformOrigin: "50% 50%",
      repeat: 4.5,
      yoyo: true,
      ease: Power4.easeOut
    }, 0.3, "start")
    .to($panda, 0.25, {
      y: -2,
      scaleX: 1.002,
      transformOrigin: "50% 50%",
      repeat: 11,
      yoyo: true,
      ease: Power3.easeInOut
    }, "start")
    .to($shadow, 0.25, {
      scaleX: 0.95,
      transformOrigin: "50% 50%",
      repeat: 10,
      yoyo: true,
      ease: Sine.easeOut
    }, "+=start0.5")
    .to($("#arm1"), 1, {
      rotation: 30,
      transformOrigin: "100% 30%",
      ease: Power4.easeOut
    }, "start+=3")
    .to($("#arm2"), 1, {
      rotation: -30,
      transformOrigin: "0% 30%",
      ease: Power4.easeOut
    }, "start+=3")
    .staggerFromTo($(".heart"), 3, {
      scale: 0,
      transformOrigin: "50% 50%"
    }, {
      scale: 10,
      opacity: 0,
      transformOrigin: "50% 50%",
      ease: Power4.easeOut
    }, 1, "start+=4")
    .to($("html,body"), 3, {
      backgroundColor: "#ad2a66",
      ease: Power4.easeOut
    }, "start+=3")
    .to($("#shadow ellipse"), 3, {
      fill: "#590647",
      ease: Circ.easeOut
    }, "start+=3")
    .to($("#arm1"), 1, {
      rotation: 0,
      transformOrigin: "100% 30%",
      ease: Power2.easeIn
    }, "start+=7")
    .to($("#arm2"), 1, {
      rotation: 0,
      transformOrigin: "0% 30%",
      ease: Power2.easeIn
    }, "start+=7");

  tl.timeScale(1.2);

  return tl;
}

// the second scene
function sceneTwo() {
  var tl = new TimelineMax(),
    sT = new SplitText($t1, {
      type: "chars"
    });

  tl.add("lasers");
  tl.to($features, 1, {
      rotationX: -5,
      x: -10,
      transformOrigin: "50% 50%",
      ease: Power2.easeIn
    }, "lasers")
    .to($("html,body"), 3, {
      backgroundColor: "red",
      ease: Power4.easeOut
    }, "lasers")
    .to($lasers, 0.25, {
      opacity: 1
    }, "lasers+=1")
    .fromTo($lasers, 1.1, {
      drawSVG: '0 0'
    }, {
      drawSVG: true
    }, "lasers+=1")
    .to($lasers, 2, {
      rotation: 0,
      x: 0,
      ease: Power3.easeIn
    }, "lasers+=2")
    .staggerFromTo(sT.chars, 1, {
      scale: 0,
      rotationY: -360
    }, {
      scale: 1,
      rotationY: 0,
      ease: Elastic.easeOut
    }, 0.08, "lasers+=3")
    .to($features, 2, {
      rotationX: 5,
      x: 10,
      transformOrigin: "50% 50%",
      ease: Power3.easeIn
    }, "lasers+=2")
    .fromTo($lasers, 0.75, {
      drawSVG: true
    }, {
      drawSVG: "0 0"
    }, "lasers+=5")
    .to($features, 2, {
      rotationX: 0,
      x: 0,
      transformOrigin: "50% 50%",
      ease: Power3.easeIn
    }, "lasers+=6");

  return tl;
}

var master = new TimelineMax();
master.add(sceneOne(), "scene1")
  .add(sceneTwo(), "scene2", "-=2");

//master.seek("scene2");