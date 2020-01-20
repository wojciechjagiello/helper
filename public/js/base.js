$(document).ready(function()
{
	//-> base
	$('select').formSelect();

	if(window.watcher)
	{
		if(!("Notification" in window))
			alert("This browser does not support system notifications");
		else if(Notification.permission !== 'denied' && Notification.permission !== "granted")
			Notification.requestPermission(function (permission){});

		runWatcher();
	}

	//-> homepage
	$("body").on("click", ".projectAction", function()
	{
		var projectName = $(this).attr('data-project');
		var key = $(this).attr('data-name');
		var dataType = $(this).attr('data-type');
		var target = $(this);

		target.append("<div class='preloader-wrapper small active'> <div class='spinner-layer spinner-green-only'><div class='circle-clipper left'><div class='circle'></div>"
		+"</div><div class='gap-patch'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div>");

		if(dataType == 'log')
		{
			var url = window.logFullContentUrl;
			var timeout = 500;
		}
		else
		{
			var url = window.runBatUrl;
			var timeout = 200;
		}

		$.ajax({
			url : url,
			type : 'POST',
			dataType : 'JSON',
			data : {
				'projectName':projectName,
				'key':key
			},
			success: function(data)
			{
				if(data.error.length)
				{
					target.children('.preloader-wrapper').remove();

					alert(data.error);
				}
				else
				{
					if(dataType == 'log')
					{
						target.children('.preloader-wrapper').remove();
						$('.logsContainer .content').empty().append(data.result);
						$('.logsContainer').show();
					} else if(dataType == 'bat')
					{
						target.children('.preloader-wrapper').remove();
					}
				}
			},
			error: function(data)
			{
				console.log(data);
				target.children('.preloader-wrapper').remove();
			},
			timeout:timeout
		});
	});

// settings
	$("body").on("click", ".closeLogsContainer", function()
	{
		$('.logsContainer').hide();
	});

	$("body").on("click", ".closeLogsContainer2", function()
	{
		$('.logsContainer').hide();
	});
	//->settings

	$("body").on("click", "#addProject", function()
	{
		$('.addProjectContainer').show();
	});

	$("body").on("click", ".addLog", function()
	{
		$(this).parent().parent().children('.addLogContainer').show();
	});

	$("body").on("click", ".addBat", function()
	{
		$(this).parent().parent().children('.addBatContainer').show();
	});

	$("body").on("click", ".editContent", function()
	{
		var container = $(this).parent().parent().parent();

		$(container).children('div').hide();
		$(container).find('.s12 ').show();
	});

	$("body").on("click", ".cancelEdit", function()
	{
		var container = $(this).parent().parent().parent();

		$(container).children('div').show();
		$(container).find('.editContainer').hide();
	});

	$('.card-alert > button').on('click', function()
	{
		$(this).closest('div.card-alert').fadeOut('slow');
	})
});

function runWatcher()
{
	$.ajax({
		url : window.watcherUrl,
		dataType: "json",
		success: function(data)
		{
			if(data)
			{
				$.each( data, function( key, value )
				{
					displayNotification(value);
				});
			}
			setTimeout(function()
			{
				runWatcher();
			}, 2000);
		},
		error: function(data)
		{
			displayNotification('Error in log watcher');

			console.log(data);

			setTimeout(function()
			{
				runWatcher();
			}, 2000);
		},
	});
}

function displayNotification(data)
{
	var title = data.projectName+' '+ data.logName;
	var content = data.content;
	var icon = window.notificationIcon;
	var url = window.notificationFullLogContent+'/'+data.cacheFileName;

	if(!("Notification" in window))
	{
		alert("This browser does not support system notifications");
	}
	else if(Notification.permission === "granted")
	{
		var notification = new Notification(title, {'body':content, 'icon':icon});

		notification.onclick = function(event)
		{
			event.preventDefault();
			window.open(url, '_blank');
		}
	}
	else if(Notification.permission !== 'denied')
	{
		Notification.requestPermission(function (permission)
		{
			if(permission === "granted")
			{
				var notification = new Notification(title, {'body':content, 'icon':icon});

				notification.onclick = function(event)
				{
					event.preventDefault();
					window.open(url, '_blank');
				}
			}
		});
	}
}
