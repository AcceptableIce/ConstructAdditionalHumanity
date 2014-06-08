	function mainVM() {
		var self = this;
		self.cards = ko.observableArray([]);
		
		self.currentlyEditingData = ko.observable();
		self.cardType = ko.observable("1");
		
		self.setName = ko.observable();
		self.setImage = ko.observable();
		self.setImageSource = ko.observable();
		
		self.exportError = ko.observable();
		
		self.getIconUrl = ko.computed(function() {
			return self.setImageSource() != undefined ? 'url(' + self.setImageSource() + ')' : 'none';
		});
		
		self.submitCard = function() {
			self.cards.push({ type: self.cardType(), value: self.currentlyEditingData().replace(/(_)+/g, "________") });
			self.currentlyEditingData("");
		}
		
		self.exportSet = function() {
			if(self.setName() == undefined || self.setName().length < 1) {
				self.exportError('Please name your set.');
				return;
			}
			
			if(self.cards().length == 0) {
				self.exportError('Your set must contain at least one card.');
				return;
			}
			
			self.exportError('');
			$.redirectPost('genSet.php', {
				name: self.setName(),
				image: self.setImageSource(),
				cards: ko.toJSON(self.cards)
			});
		}
		
		self.sortedCards = ko.computed(function() {
			return self.cards().sort(function(left, right) {
				return left.type == right.type ? 0 : ((left.type - right.type) < 0 ? -1 : 1);
			})
		});
		
		
		$("#cardEdit").keypress(function(ev) {
			if((ev.ctrlKey || ev.metaKey) && ev.which == 13) {
				ev.preventDefault();
				self.submitCard();
			}
			
		});
		self.deleteCard = function(data) {
			self.cards.remove(data);
		}
		
		self.saveSet = function() {
			if(self.setName() == undefined || self.setName().length < 1) {
				self.exportError('Please name your set.');
				return;
			}
			
			if(self.cards().length == 0) {
				self.exportError('Your set must contain at least one card.');
				return;
			}
			self.exportError('');
			$.redirectPost('saveSet.php', {
				name: self.setName(),
				image: self.setImageSource(),
				cards: ko.toJSON(self.cards)
			});
		}
		
		function handleFileSelect(evt) {
			evt.stopPropagation();
			evt.preventDefault();

			console.log('drop');
			var file = evt.dataTransfer.files[0];
			if(file.type != 'image/png') {
				//only PNG error;
				console.log('Only PNGs are accepted');
				return;
			}
			if(file.size > 10000) {
				//too big file error
				console.log('File too large');
				return;
			}
			self.setImage(file);
			var reader = new FileReader();
			reader.onload = (function(file) {
				return function(e) {
					console.log('Loaded image');
					$("#imgThumb").css("background-image", 'url(' + e.target.result + ')');
					$("#imgThumb").css("background-color", "transparent");
					$("#imgThumb").html("&nbsp;");
					self.setImageSource(e.target.result);
				}
			})(file);
			reader.readAsDataURL(file);
		}
		
		function handleImport(evt) {
			evt.stopPropagation();
			evt.preventDefault();

			console.log('drop');
			var file = evt.dataTransfer.files[0];
			console.log(file.type);
			if(file.type != 'application/json') {
				//only PNG error;
				console.log('Only JSON files are accepted');
				return;
			}
			var reader = new FileReader();
			reader.onloadend = function(file) {
				console.log('Loaded json');
				console.log(this.result);
				var data = JSON.parse(this.result);
				self.setName(data.name);
				self.setImageSource(data.icon);
				self.cards(data.cards);
				
			};
			reader.readAsText(file);
		}
		function handleDragOver(evt) {
		    evt.stopPropagation();
		    evt.preventDefault();
		    evt.dataTransfer.dropEffect = 'copy'; // Explicitly show this is a copy.
		}
		
		document.getElementById('imgThumb').addEventListener('dragover', handleDragOver, false);
		document.getElementById('imgThumb').addEventListener('drop', handleFileSelect, false);
		
		document.body.addEventListener('dragover', handleDragOver, false);
		document.body.addEventListener('drop', handleImport, false);
	}
	
	ko.applyBindings(new mainVM());
	
	$.extend({
    redirectPost: function(location, args)
    {
        var form = '';
        $.each( args, function( key, value ) {
            form += '<input type="hidden" name="'+key+'" value=\'' + (value ? value.replace('\'', '&#39;') : 'undefined') +'\'>';
        });
        $('<form action="'+location+'" method="POST">'+form+'</form>').submit();
    }
});
