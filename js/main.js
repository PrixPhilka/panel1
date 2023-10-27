
$(`a[date-page="${$('[name="page-name"]').val()}"]`).addClass('active');




$('.openclosemenu').click(function(){
	if ($('main').hasClass('hidemenu')) {
		$('main').removeClass('hidemenu');
	} else {
		$('main').addClass('hidemenu');
	}
});
$(document).on('click', '#statisticform table thead td', function(){
	if ($(this).attr('data-type') !== undefined && $(this).attr('data-type') !== null) {
		if (!$(`input[name="${$(this).attr('data-type')}"]`).length) {
			$('#statisticform').append(`<input type="hidden" name="${$(this).attr('data-type')}" value="DESC">`);
		} else {
			if ($(`input[name="${$(this).attr('data-type')}"]`).val() == 'DESC') {
				$(`input[name="${$(this).attr('data-type')}"]`).val('ASC');
			} else {
				$(`input[name="${$(this).attr('data-type')}"]`).val('DESC');
			}
		}
		$('#statisticform').submit();
	}


});

$(document).on('click', '.refresh > a:nth-child(1)',function(){
	$('#statisticform').submit();
});


$(document).on('click', '.refresh > a:nth-child(2)',function(){
	$('#statisticform input').val('');
	$('#statisticform').submit();
});

$(document).on('change', '#limit',function(){
	$('#statisticform').submit();
});


$(document).on('submit', 'form', function(){
	if ($(this).attr('data-type')) {
		return true;
	}
	$.ajax({
		type: $(this).attr('method'),
		url: $(this).attr('action'),
		dataType: 'html',
		beforeSend: loadingstart,
		data: $(this).serialize(),
		success: function(data) {
			loadingend();
			$('section.main-content').html($(data).find('section.main-content').html());
			history.pushState('', '', $(data).find('#youurl').val());
			BeatuCheck();
		}
	});
	return false;
});
function loadingstart(){
	$('section.main-content').append(`<div class="loadbox">
	<div class="loader"></div>
</div>`);
}

function loadingend(){
	$('section.main-content .loadbox').remove();
}

$(document).on('click', 'a', function(){
	if ($(this).attr('data-type') || $(this).attr('target')) {
		return true;
	}
	if ($(this).attr('href') !== 'javascript:;') {
		$.ajax({
		  url: $(this).attr('href'),
		  beforeSend: loadingstart,
		  success: function(data){
		  	$('.left-menu > ul > li > a').removeClass('active');
		  	$(`ul > li > a[date-page="${$(data).find('[name="page-name"]').val()}"]`).addClass('active');
		  	loadingend();
		  	$('section.main-content').html($(data).find('section.main-content').html());
		  	history.pushState('', '', $(data).find('#youurl').val());
				BeatuCheck();
		  },
		  dataType: 'html'
		});
	}



	return false;
});

	if (document.documentElement.clientWidth <= 1000) {
		$('main').addClass('hidemenu');
	}
window.addEventListener('resize', function(e){
	if (document.documentElement.clientWidth <= 1000) {
		$('main').addClass('hidemenu');
	} else {
		$('main').removeClass('hidemenu');
	}
});


$(document).on('click', '.viewlog', function(){
  var type = 'data:application/octet-stream;base64, ';
  var text = $(this).find('textarea').val();
  var base = btoa(text);
  var res = type + base;
  $(this).attr('href', res);
  return true;
});




BeatuCheck();

function Select(yourselect) {

    yourselect.style.display = 'none';
    let newdiw = document.createElement("div");
    let parentDiv = yourselect.parentNode;
    let start_span;
    parentDiv.insertBefore(newdiw, yourselect);
    newdiw.classList.add('selectel');


    if (yourselect.dataset.description !== undefined) {
    	start_span = yourselect.dataset.description;
    } else if(yourselect.querySelector('option').dataset.image !== undefined){
    	start_span = `<img src="${yourselect.querySelectorAll('option')[yourselect.selectedIndex].dataset.image}">${yourselect.querySelectorAll('option')[yourselect.selectedIndex].text}`;
    } else {
    	start_span = yourselect.querySelectorAll('option')[yourselect.selectedIndex].text;
    }
  
    newdiw.innerHTML = `<div class="button">
		<span>${start_span}</span>
		<svg width="17" height="10" fill="none"><path d="M1 1.056L8.5 9 16 1" stroke="#372D6C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg> 
		</div>
		<div class="selectlist"> 
		</div>`;
  
    let list = newdiw.querySelector('.selectlist');
    yourselect.querySelectorAll('option').forEach(element => {
        if (element.dataset.image !== undefined) {
            list.innerHTML += `<div data-id="${element.index}"><img src="${element.dataset.image}">${element.text}</div>`;
        } else {
            list.innerHTML += `<div data-id="${element.index}">${element.text}</div>`;
        }


    });
  
    newdiw.querySelector('.button').onclick = function() {
        event.stopPropagation();
        if (newdiw.classList.contains('active')) newdiw.classList.remove("active");
        else newdiw.classList.add("active");
    }
    
  
      document.body.addEventListener('click', () => {
        if (newdiw.classList.contains("active")) newdiw.classList.remove("active");
      });

  
  
    newdiw.querySelectorAll('.selectlist > div').forEach(element => {
        element.onclick = function() {
            newdiw.querySelectorAll('.selectlist > div').forEach(element => {
                element.classList.remove('active');
            });

            if (this.querySelector('img') !== null) {
                newdiw.querySelector('.button > span').innerHTML = `<img src="${this.querySelector('img').getAttribute('src')}">` + this.innerText;
            } else {
                newdiw.querySelector('.button > span').innerText = this.innerText;
            }
          
            this.classList.add('active');
            newdiw.classList.remove("active");
            yourselect.options[this.dataset.id].selected = true;
        }
    });

    newdiw.querySelectorAll('.selectlist > div')[yourselect.selectedIndex].classList.add('active');
}





function BeatuCheck() {
	if (document.querySelector('.beatu-select') !== undefined && document.querySelector('.beatu-select') !== null &&  document.querySelector('.beatu-select').length) {
		document.querySelectorAll('.beatu-select').forEach((elem)=>{
			Select(elem)
		});
	}
	return false;
}

$(document).on('click', '.tablist > a', function(){
	$('.tablist > a').removeClass('active');
	$('.tabcontent > form').css('display', 'none');
	$('.tabcontent > form').eq($(this).index()).css('display' ,'block');
	$(this).addClass('active');
});


$(document).on('click', '.static_time > a', function(){
	$('.static_time > a').removeClass('active');
	$(this).addClass('active');
	loadingstart();
	$.ajax({
		url: `/?page=root&p=static&day=${$(this).attr('data-day')}`,
		dataType: 'html',
		success: function(data) {
			loadingend();
			$('.tableblock').html( $('.tableblock', data).html() );
		}
	});

});

