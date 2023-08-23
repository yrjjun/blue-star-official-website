!function () {
	function $add_mask() {
		var $element = document.createElement("div");
		$element.classList.add("app_mask");
		document.body.append($element);
		var $onclick_list = [];
		var $root = {
			$element,
			$closed: false,
			$close: function () {
				this.$closed = true;
				$element.classList.remove("app_mask_display");
				setTimeout(() => {
					$element.remove();
				}, 300);
			},
			$onclick: function ($callback) {
				$onclick_list.push($callback);
			}
		};
		setTimeout(() => {
			$element.classList.add("app_mask_display");
		}, 100);
		$element.addEventListener("click", function ($event) {
			$onclick_list.forEach(($callback) => {
				$callback($event);
			});
		});
		return $root;
	};

	function $add_msg(text) {
		var $element = document.createElement("div");
		$element.classList.add("app_amsg");
		$element.innerText = text;
		$(".app_msg").append($element);
		var $onclick_list = [];
		var $root = {
			$element,
			$closed: false,
			$close: function () {
				this.$closed = true;
				$element.classList.remove("app_amsg_display");
				setTimeout(() => {
					$element.remove();
				}, 300);
			},
			$onclick: function ($callback) {
				$onclick_list.push($callback);
			}
		};
		setTimeout(() => {
			$element.classList.add("app_amsg_display");
		}, 100);
		setTimeout(() => {
			$root.$close();
		}, 5000);
		$element.addEventListener("click", function ($event) {
			$onclick_list.forEach(($callback) => {
				$callback($event);
			});
		});
		return $root;
	};

	var app_content_product = $(".app_content_product");
	var app_content_product_cards = $(".app_content_product_card");
	app_content_product.each(() => {
		app_content_product.css("cssText", `--width:calc((100% - 100px) / ${app_content_product_cards.length})`)
	});
	var new_things = $(".new_things");
	var new_things_cards = $(".new_things_cards");
	new_things.each(() => {
		new_things.css("cssText", '--width:'+ $(".new_things").width()*0.2+'px;');
	});

/*
	$(".recognize .app_content_subfield_content ").text($(".recognize .app_content_subfield_content ").text() +
		``)*/
}();