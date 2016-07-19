;JSON = JSON || {};
if (typeof Object.toJSON == 'function') {JSON.stringify = function(value) { return Object.toJSON(value); };}
JSON.parse = JSON.parse || function(jstr) { return jstr.evalJSON(true); }

;(function($){
	$(document).ready(function(){

		var widgetStatus = {
			WIDGET_MINIMIZE: "WIDGET_MINIMIZE",
			WIDGET_OPEN: "WIDGET_OPEN",
			WIDGET_HIDE: "WIDGET_HIDE",
		};

		var widget = {
			config: {
				app_id: '',
				user_id: '',
				user_name: '',
				access_token: '',
				image_url: '',

				// AJAX Call: true,
				internal_users_url: '//content.copypress.com/agora/user.php',
				//##internal_users_url: '//content.copypress.com/js/agora/userlist.json',
				calllogs_url: '//content.copypress.com/agora/signalling.php',

				// debug: true,
				chatChannel: '',
				chatChannelURL: '',
				assets_url: '//content.copypress.com/js/agora',//default is /assets

				userlist_update_interval: 50, // seconds
				slide_time: 600,
				main_widget_width: 783,//683
				main_widget_height: 464,//364
				main_widget_caption_height: 40,

				main_widget_html: '<div class="container chat-widget"><div class="row"><div class="col-xs-12"><div class="panel panel-primary"><div class="panel-heading"><span class="glyphicon glyphicon-comment"></span> <div class="caption-title">Chat</div><div class="btn-group pull-right"><button type="button" class="btn btn-default btn-xs dropdown-toggle js-tool-dropdown" data-toggle="dropdown"><span class="glyphicon glyphicon-chevron-down"></span></button><ul class="dropdown-menu slidedown" style="display:none;"><li><a href="javascript:;"><span class="glyphicon glyphicon-ok-sign"></span>Available</a></li><li><a href="javascript:;"><span class="glyphicon glyphicon-remove"></span>Busy</a></li><li><a href="javascript:;"><span class="glyphicon glyphicon-time"></span>Away</a></li><li class="divider"></li><li><a href="javascript:;"><span class="glyphicon glyphicon-off"></span>Sign Out</a></li></ul></div><button type="button" class="btn btn-default btn-xs dropdown-toggle pull-right mgr10 js-min-max-box"><span class="glyphicon glyphicon-fullscreen"></span></button></div><div class="panel-body" style="display:none;"><div class="chat-content col-xs-8"><ul class="chat"></ul></div><div class="channel-list col-xs-4"><div class="userlist-unread group"></div><div class="userlist-online group"></div><ul class="channel"></ul></div></div><div class="panel-footer"  style="display:none;"><div class="input-group"><input id="btn-input" type="text" class="form-control input-sm" placeholder="Type your message here..." /><span class="input-group-btn"><button class="btn btn-warning btn-sm" id="btn-chat">Send</button></span></div></div></div></div></div></div>',

				agora_html: '<div class="agora-screen agora-wait"><div class="left"><div class="" id="agora_local"></div></div><div class="right small-window"><ul><li class="remoteVideo"></li></ul></div><div class="agora-options"><ul><li class="audioSwitch" title="Mute"><div class="on"></div></li><li class="videoSwitch" title="Camera Off"><div class="on"></div></li><li class="viewSwitch" title="Switch View"><div class="on"></div></li><li class="leave"><div class="four"></div><p class="telephone"></p></li></ul></div><div class="agora-content small-window"><p>Waiting for attendees...</p><img class="leave" src="/js/agora/assets/telephone.png" width="50" height="50"/></div><div class="agora-info"></div></div>',
			},

			mainWidgetStatus: {
				scrollHandler: false,
				//widgetStatus: widgetStatus.WIDGET_OPEN,
				widgetStatus: widgetStatus.WIDGET_MINIMIZE,
				readyToMessaging: true,
			},

			chatWidgetStatus: {
				scrollHandler: false,
				widgetStatus: widgetStatus.WIDGET_HIDE,
				chatChannel: '',
				typingTimeoutHandler: false,
				chatUserId: '',
				chatUserName: '',
			},

			init: function(cb){
				var self = this;

				self.initSendBirdSDK(function(){
					self.setMessageEventHandler();

					self.createMainWidget(function(){
						// self.mainWidgetContentBody.find('.caption__title__button').html(self.config.user_id);
						self.setMainWidgetEventHandler();


						self.updateUserList(function(){
							self.updateUnreadCount(function(){
								sendbird.connect();
								self.mainWidgetUserListUpdate();
							});
						});

					});
				});
			},

			initSendBirdSDK: function(cb){
				var self = this;

				// SendBird Initialize
				sendbird.init({
					"app_id": self.config.app_id,
					"guest_id": self.config.user_id,
					"user_name": self.config.user_name,
					"image_url": self.config.image_url,
					"access_token": self.config.access_token,
					"successFunc": function(data) {
						// init success
						cb();
					},
					"errorFunc": function(status, error) {
						// console.log(status, error);
						// do something
					}
				});
			},

			/**
			* Main Widget 
			*/
			createMainWidget: function(cb){
				var self = this;
				$('<div id="sb-widget" style="z-index:999999;display:none;margin:0;padding:0;position:fixed;bottom:0;right:30px;width:'+self.config.main_widget_width+'px;height:'+self.config.main_widget_caption_height+'px;overflow:hidden;-webkit-box-shadow: 2px 2px 5px 0px rgba(186,186,186,1);-moz-box-shadow: 2px 2px 5px 0px rgba(186,186,186,1);box-shadow: 2px 2px 5px 0px rgba(186,186,186,1);border-top-left-radius: 6px;border-top-right-radius: 6px;"><iframe width="100%" height="100%" frameborder="0"></iframe></div>').prependTo('body');

				setTimeout(function(){
					self.mainWidget = $('#sb-widget');
					
					self.mainWidgetFrame = $('#sb-widget iframe');
					self.mainWidgetContents = self.mainWidgetFrame.contents();
					self.mainWidgetBody = self.mainWidgetContents.find('body');
					self.mainWidgetDocument = self.mainWidgetFrame[0].contentWindow.document;
					var head = self.mainWidgetDocument.getElementsByTagName("head")[0];

					// Import CSS
					$.each(["css/bootstrap.min.css","css/font-awesome.min.css","css/chat.css"],function(i,value){
						$("<link/>", {
						   rel: "stylesheet",
						   type: "text/css",
						   href: self.config.scriptURL+value+"?v=20160304"+Math.random()
						}).appendTo(head);
					});

					// Import Widget HTML
					setTimeout(function(){
						self.mainWidgetBody.html(self.config.main_widget_html);
						self.bindEventToPageUser();
						self.mainWidgetContentBody = self.mainWidgetBody.find('.channel-list').first();
						self.mainWidgetContentBodyContent = self.mainWidgetBody.find('ul.channel').first();
						self.showPanelDropDown();
						//self.chatWidgetContentBody = self.mainWidgetBody.find('.panel-body').first();
						//self.chatWidgetContentBodyContent = self.mainWidgetBody.find('.panel-body .chat-content').first();
						self.showMainWidget(function(){
							if (typeof cb == 'function') {
								cb();
							}
						});
					}, 0);
				}, 0);
			},

			showMainWidget: function(cb){
				var self = this;
				self.mainWidget.fadeIn(500, function(){
					if (typeof cb == 'function') {
						cb();
					}
				});
			},

			setMainWidgetEventHandler: function(cb){
				var self = this;

				// Main Widget Caption Click
				self.mainWidgetContents.find('.js-min-max-box').on('click', function(){
					switch(self.mainWidgetStatus.widgetStatus){
						case widgetStatus.WIDGET_OPEN:
							self.resizeMainWidget(widgetStatus.WIDGET_MINIMIZE);
							break;
						case widgetStatus.WIDGET_MINIMIZE:
							self.resizeMainWidget(widgetStatus.WIDGET_OPEN);
							break;
					};

				});

				// Main Widget List Item Click
				self.mainWidgetContents.on('click', '.channel-list ul.channel li',  function(e){
					e.stopPropagation();
					var target_user_id = $(this).attr('data-id');
					var target_user_name = $(this).attr('data-name');
					//var target_channel = $(this).attr('data-channel');
					//console.log(target_user_name);
					$(this).parent().children("li").removeClass("active");
					$(this).addClass("active");

					if (self.mainWidgetStatus.readyToMessaging === false) {
						return;
					}
					self.mainWidgetStatus.readyToMessaging = false;

					sendbird.startMessaging(
						[target_user_id],
						{
							"successFunc" : function(data) {
								//console.log(data);

								self.chatWidgetStatus.chatChannel = data.channel.id;
								self.chatWidgetStatus.chatChannelURL = data.channel.channel_url;
								self.chatWidgetStatus.chatUserId = target_user_id;
								self.chatWidgetStatus.chatUserName = target_user_name;
								sendbird.connect({
									"successFunc" : function(data) {
										self.createChatWidget(function(){
											self.showChatWidget(function(){
												//console.log(data);
												// do something
												self.mainWidgetSetCaption("Chat With " + target_user_name);
												self.chatWidgetFocus();

												sendbird.getMessageLoadMore({
													"limit": 50,
													"successFunc" : function(data) {
														self.mainWidgetStatus.readyToMessaging = true;
														/*
														console.log(data);
														moreMessage = data["messages"];
														$.each(moreMessage.reverse(), function(index, msg) {
															console.log(item);
														});
														*/
														for (var i in data.messages) {
															var message = data.messages[i];
															switch (message.cmd) {
																case 'MESG':
																	if (message.payload.channel_id != self.chatWidgetStatus.chatChannel) {
																		break;
																	}

																	if (message.payload.user.guest_id == self.config.user_id) {
																		self.chatWidgetAddMessage('send_message', message.payload.message, {user:{name:message.payload.user.name,msgts:message.payload.sts}}, true);
																	} else {
																		self.chatWidgetAddMessage('receive_message', message.payload.message, {user:{name:message.payload.user.name,msgts:message.payload.sts}}, true);
																		self.setUnreadCount(message.payload.user.guest_id, 0);
																	}

																	break;
																default:
																	// console.log(message.cmd);
																	// console.log(message);
															}
														}
														self.setMarkAsRead(target_user_id);
														self.chatWidgetFocus();
													},
													"errorFunc": function(status, error) {
														console.log(status, error);
														// do something
													}
												});
											});
										});
									},
									"errorFunc": function(status, error) {
										console.log(status, error);
										// do something
										self.mainWidgetStatus.readyToMessaging = true;
									}
								});
								// do something
							},
							"errorFunc": function(status, error) {
								console.log(status, error);
								// do something
							}
						}
					);

					/*
					self.mainWidgetContentBodyContent.find('li.channel-user span i').on('click', function(e){
						e.stopPropagation();
						self.clearChatWidget();
						var grandpaele = $(this).parent().parent();
						var target_user_id = grandpaele.attr('data-id');
						var target_user_name = grandpaele.attr('data-name');

						self.agoraWidgetStatus.prevChatChannel = self.agoraWidgetStatus.chatChannel;
						if (self.config.user_id.toString() > target_user_id.toString()) {
							self.agoraWidgetStatus.chatChannel = "chl-" + target_user_id + "-" +  self.config.user_id;
						} else {
							self.agoraWidgetStatus.chatChannel = "chl-" + self.config.user_id + "-" + target_user_id;
						}
						console.log(self.agoraWidgetStatus.chatChannel);
						self.agoraWidgetStatus.chatUserName = target_user_name;
						self.agoraWidgetStatus.chatUserId = target_user_id;

						//$(this).parentsUntil(".channel-user").removeClass("active");
						//$(this).parentsUntil(".channel-user").addClass("active");
						grandpaele.siblings("li.channel-user").removeClass("active");
						grandpaele.addClass("active");
						//console.log(self.config.chatChannel);
						//console.log(self.agoraWidgetStatus.chatChannel, self.agoraWidgetStatus.chatUserName);
						//self.chatWidgetContentBody.find('ul.chat').html(self.config.agora_html);
						self.mainWidgetBody.find('.panel-body ul.chat').html(self.config.agora_html);

						self.initAgoraRTC();
					});
					*/
				});

				//self.mainWidgetContentBodyContent.find('li.channel-user span i').on('click', function(e){
				self.mainWidgetContents.on('click', '.channel-list ul.channel li.channel-user span i',  function(e){
					e.stopPropagation();
					self.clearChatWidget();
					var grandpaele = $(this).parent().parent();
					var target_user_id = grandpaele.attr('data-id');
					var target_user_name = grandpaele.attr('data-name');

					self.agoraWidgetStatus.prevChatChannel = self.agoraWidgetStatus.chatChannel;
					if (self.config.user_id.toString() > target_user_id.toString()) {
						self.agoraWidgetStatus.chatChannel = "chl-" + target_user_id + "-" +  self.config.user_id;
					} else {
						self.agoraWidgetStatus.chatChannel = "chl-" + self.config.user_id + "-" + target_user_id;
					}
					console.log(self.agoraWidgetStatus.chatChannel);
					self.agoraWidgetStatus.chatUserName = target_user_name;
					self.agoraWidgetStatus.chatUserId = target_user_id;

					//$(this).parentsUntil(".channel-user").removeClass("active");
					//$(this).parentsUntil(".channel-user").addClass("active");
					grandpaele.siblings("li.channel-user").removeClass("active");
					grandpaele.addClass("active");
					//console.log(self.config.chatChannel);
					//console.log(self.agoraWidgetStatus.chatChannel, self.agoraWidgetStatus.chatUserName);
					//self.chatWidgetContentBody.find('ul.chat').html(self.config.agora_html);
					self.mainWidgetBody.find('.panel-body ul.chat').html(self.config.agora_html);

					self.initAgoraRTC();

					self.addAgoraCallerLog();
				});


				/*
				$('#test', window.parent.document).on("click",function(){
					console.log("eeee");
					self.mainWidgetContents.find('.channel-list ul.channel').append("<li data-id='11' class="channel-user" data-name='superlel'><div class="user-unreadcount"></div><div class="user-name">Leo</div></li>");
				});
				*/
			},

			mainWidgetScrollTop: function(){
				var self = this;
				
				if (self.mainWidgetStatus.scrollHandler) {
					clearTimeout(self.mainWidgetStatus.scrollHandler);
				}

				self.mainWidgetStatus.scrollHandler = setTimeout(function(){
					self.mainWidgetContentBody.animate({scrollTop: 0}, 100, function(){
						self.mainWidgetStatus.scrollHandler = false;
					});
				}, 100);
			},

			resizeMainWidget: function(status, cb) {
				var self = this;
				// console.log('Main Widget Resize Called: '+status);
				switch (status) {
					case widgetStatus.WIDGET_MINIMIZE:
						self.mainWidgetBody.find('#btn-chat').first().prop('disabled', true);

						self.mainWidgetBody.find(".panel-body").slideUp(widget.config.slide_time);
						self.mainWidgetBody.find(".panel-footer").slideUp(widget.config.slide_time);
						self.mainWidgetBody.find("ul.dropdown-menu").hide();
						self.mainWidgetBody.find(".panel-heading span.glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-fullscreen");
						self.mainWidget.animate({height: self.config.main_widget_caption_height + 'px'}, widget.config.slide_time, function(){
							//self.mainWidgetBody.find('.panel-body').hide(0);
							//self.mainWidgetBody.find('.panel-footer').hide(0);
						});

						self.mainWidgetStatus.widgetStatus = widgetStatus.WIDGET_MINIMIZE;
						break;
					case widgetStatus.WIDGET_OPEN:
						//###self.mainWidgetSetCaption('');//2016/4/13

						self.mainWidgetBody.find('#btn-chat').first().prop('disabled', false);

						//self.mainWidgetBody.find('.panel-body').show(0);
						//self.mainWidgetBody.find('.panel-footer').show(0);
						self.mainWidgetBody.find(".panel-body").slideDown(widget.config.slide_time);
						self.mainWidgetBody.find(".panel-footer").slideDown(widget.config.slide_time);
						self.mainWidgetBody.find(".panel-heading span.glyphicon-fullscreen").removeClass("glyphicon-fullscreen").addClass("glyphicon-minus");

						self.mainWidget.animate({height: self.config.main_widget_height + 'px'}, widget.config.slide_time);
						self.mainWidgetStatus.widgetStatus = widgetStatus.WIDGET_OPEN;
						break;
				}
			},

			mainWidgetSetCaption: function(text){
				var self = this;
				if (text) {
					self.mainWidgetBody.find('.panel-heading .caption-title').html('CP Chat - '+text);
				} else {
					self.mainWidgetBody.find('.panel-heading .caption-title').html('CP Chat');
				}
			},

			mainWidgetUserListUpdate: function(cb){
				// userlist update by user element attribute

				var self = this;
				// var movedUnreadUser = false;

				self.mainWidgetContentBodyContent.find('.channel-user').each(function(idx, elem){
					if (parseInt($(elem).attr('data-unread-count')) > 0 && !$(elem).hasClass('unread-group')) {
						// move unread count user

						// movedUnreadUser = true;

						$(elem).addClass('unread-group').removeClass('online-group offline-group');
						$(elem).insertBefore(self.mainWidgetContentBodyContent.find('.userlist-unread').first());
					} else if (parseInt($(elem).attr('data-unread-count')) == 0 && $(elem).attr('data-is-online') == 'true' && !$(elem).hasClass('online-group')) {
						// move online user

						$(elem).addClass('online-group').removeClass('unread-group offline-group');
						$(elem).insertBefore(self.mainWidgetContentBodyContent.find('.userlist-online').first());
					} else {
						// move offline user

						if (!$(elem).hasClass('offline-group') && $(elem).attr('data-is-online') == 'false' && parseInt($(elem).attr('data-unread-count')) == 0) {
							$(elem).addClass('offline-group').removeClass('unread-group online-group');
							// $(elem).insertAfter(self.mainWidgetContentBodyContent.find('.user').first());
							$(elem).insertBefore(self.mainWidgetContentBodyContent.find('.userlist-offline').first());
						}
					}
				});

				// if (movedUnreadUser) {
				// console.log('move loop start');
				self.mainWidgetContentBodyContent.find('.channel-user.unread-group').each(function(idx, elem){
					self.mainWidgetContentBodyContent.find('.channel-user.unread-group').each(function(idx2, elem2){

						var user1 = $(elem);
						var user2 = $(elem2);

						if (user1.attr('data-last-message-ts') > user2.attr('data-last-message-ts') && idx > idx2) {
							$(elem).insertBefore($(elem2));
						}
					});
				});					
				// }

			},

			mainWidgetScrollBottom: function(){
				var self = this;
				
				if (self.mainWidgetStatus.scrollHandler) {
					clearTimeout(self.mainWidgetStatus.scrollHandler);
				}

				self.mainWidgetStatus.scrollHandler = setTimeout(function(){
					self.mainWidgetContentBody.animate({scrollTop: self.mainWidgetContentBodyContent.height()}, 100, function(){
						self.mainWidgetStatus.scrollHandler = false;
					});
				}, 100);
			},

			/**
			* Chat Widget 
			*/
			createChatWidget: function(cb){
				var self = this;
				if (self.chatWidget) {
					self.clearChatWidget();
					cb();
					return;
				}


				setTimeout(function(){
					self.chatWidget = self.mainWidgetBody.find('.panel-body .chat-content');
					self.chatWidgetBody = self.chatWidget.find('ul.chat');

					// Import Widget HTML
					self.chatWidgetBody.empty();
					setTimeout(function(){
						self.chatWidgetContentBody = self.mainWidgetBody.find('.panel-body .chat-content').first();
						self.chatWidgetContentBodyContent = self.mainWidgetBody.find('ul.chat').first();
						self.setChatWidgetEventHandler(function(){
							if (typeof cb == 'function') {
								cb();
							}
						});
					}, 0);					
				}, 0);

			},

			setMarkAsRead: function(user_id){
				var self = this;

				if (self.chatWidgetStatus.widgetStatus == widgetStatus.WIDGET_MINIMIZE || self.chatWidgetStatus.widgetStatus == widgetStatus.WIDGET_HIDE) {
					return;
				}
				sendbird.markAsRead(self.chatWidgetStatus.chatChannelURL);

				if (user_id) {
					self.setUnreadCount(user_id, 0);
				}
			},
			updateUserList: function(cb){
				var self = this;

				$.ajax({
					'type': 'GET',
					'dataType': 'json',
					//'dataType': 'html',
					//'url': "js/userlist.json?v=1232121",
					'url': self.config.internal_users_url,
					'data': 'uid='+self.config.user_id+"&act=get",
					'success':function(user_data){
						if (user_data['users'].length > 0) {
							for (var i in user_data['users']) {
								var user = user_data['users'][i];
								if (user.invite_ids == undefined || user.user_id == undefined) {
									continue;
								}
								// console.log(user.is_online);
								user.is_online = true;//###we will remove this line.
								if (self.config.user_id == "user" + user.invite_ids) {
									user.guest_id = "user" + user.user_id;
									user.nickname = user.user_name;
								} else {
									user.guest_id = "user" + user.invite_ids;
									user.nickname = user.invite_users;
								}
								//console.log(user);

								/*
								if (user.guest_id == self.config.user_id) {
									continue;
								}
								*/

								var user_info = self.mainWidgetGetUser(user.guest_id);
								if (user_info.user_name) {
									if (user_info.user_name != user.nickname || user_info.user_image != user.picture) {
										self.mainWidgetUpdateUser(user.guest_id, user.nickname, user.picture);
									}
								}

								user.picture = "";
								if (user.is_online) {
									// console.log("is_online: "+user.guest_id);
									if (!self.setUserOnline(user.guest_id)) {
										self.mainWidgetAddUser(user.guest_id, user.nickname, user.picture, 0, user.is_online);
									}
								} else {
									if (!self.setUserOffline(user.guest_id)) {
										self.mainWidgetAddUser(user.guest_id, user.nickname, user.picture, 0, user.is_online);
									}
								}
							}

						}
						setTimeout(function(){
							self.updateUserList(function(){
								self.updateUnreadCount(function(){
									self.mainWidgetUserListUpdate();
								});
							});
						}, self.config.userlist_update_interval * 1000);

						if (typeof cb == 'function') {
							cb();
						}
					},
					'error':function(XHR, TS, errMsg){
						console.log(TS);
						console.log(errMsg);
					},
					'complete':function(XHR,TS){XHR = null;}
				});
			},


			updateUnreadCount: function(cb){
				var self = this;

				sendbird.getPrivateChannelList({
					"page": 1,
					"limit": 99999,
					"successFunc": function(channel_data){
						if (channel_data['channels'] > 0) {
							for (var i in channel_data['channels']) {
								var channel = channel_data['channels'][i];
								var last_message_ts = JSON.parse(channel.last_message.substring(4)).ts;
								var members = channel.members;
								for (var mi in members) {
									if (members[mi].guest_id != self.config.user_id) {
										if (channel.unread_message_count > 0) {
											console.log(channel.unread_message_count);
											self.setUnreadCount(members[mi].guest_id, channel.unread_message_count, last_message_ts);
										}
										break;
									}
								}
							}
						}

						if (typeof cb == 'function') {
							cb();
						}
					},
					"errorFunc": function(status, error){
						
					}
				});

				self.updateAgoraCalleeNotice();
			},

			setUnreadCount: function(user_id, unread_count, last_message_ts){

				//console.log(user_id, unread_count, last_message_ts);

				var self = this;
				if (!unread_count || parseInt(unread_count) == 0) {
					unread_count = '0';
				} else {
					// self.mainWidgetScrollTop();
				}

				var user_elem = self.mainWidgetContentBodyContent.find('.channel-user[data-id="%user_id%"]'.replace('%user_id%',user_id)).first();
				var before_unread_count = user_elem.find('.user-unreadcount').html();

				// user_elem.attr('ts', ts);
				user_elem.attr('data-unread-count', unread_count);
				if (last_message_ts) {
					// console.log('ts update: '+last_message_ts);
					user_elem.attr('data-last-message-ts', last_message_ts);
				}

				if (parseInt(unread_count) > 0) {
					user_elem.find('.user-unreadcount').html(unread_count);
				} else {
					user_elem.find('.user-unreadcount').html('');
				}

				// self.mainWidgetUserListUpdate();
			},

			setMessageEventHandler: function(){
				var self = this;

				sendbird.events.onMessageReceived = function(obj) {
					if (!self.chatWidget) {
						return;
					}

					if (self.chatWidgetStatus.widgetStatus == widgetStatus.WIDGET_HIDE) {
						return;
					}
					
				  // openned channel
				  if (obj.channel_id == self.chatWidgetStatus.chatChannel) {
			  		// self message
			  		if (obj.user.guest_id == self.config.user_id) {
				  		self.chatWidgetAddMessage('send_message', obj.message, {user:{name:obj.user.name,msgts:obj.sts}});
			  		} else {
			  			self.chatWidgetAddMessage('receive_message', obj.message, {user:{name:obj.user.name,msgts:obj.sts}});


			  			switch (self.chatWidgetStatus.widgetStatus) {
			  				case widgetStatus.WIDGET_OPEN:
			  					self.setMarkAsRead(obj.user.guest_id);
			  					break;
			  				case widgetStatus.WIDGET_MINIMIZE:
			  					self.chatWidgetSetCaption('NEW MESSAGE!');
			  					break;
			  			}
			  		}
				  }
				};

				// MCUP
				sendbird.events.onMessagingChannelUpdateReceived = function(obj) {
				  var channel_id = obj.channel.id;

				  // console.log(obj);
				  for (var i in obj.members) {
				  	var user = obj.members[i];
				  	if (self.config.user_id == user.guest_id) {
				  		continue;
				  	}
				  	self.setUnreadCount(user.guest_id, obj.unread_message_count);
				  }

				  if (self.mainWidgetStatus.widgetStatus == widgetStatus.WIDGET_MINIMIZE) {
				  	if (self.chatWidgetStatus.widgetStatus == widgetStatus.WIDGET_OPEN && obj.channel.id == self.chatWidgetStatus.chatChannel) {
				  		// pass
				  	} else {
				  		self.mainWidgetSetCaption('NEW MESSAGE');
				  	}
				  }
				};

				sendbird.events.onTypeStartReceived = function(obj) {
					// console.log('onTypeStartReceived');
					// console.log(obj);
					if (obj.user.guest_id != self.config.user_id) {
						self.showChatWidgetTyping(obj);
					}
				};

				sendbird.events.onTypeEndReceived = function(obj) {
					// console.log('onTypeEndReceived');
					self.hideChatWidgetTyping(obj);
					// console.log(obj);
				};
			},

			setUserOnline: function(user_id){
				var self = this;
				var user_elem;

				try {
					user_elem = self.mainWidgetContentBodyContent.find('.channel-user[data-id="%user_id%"]'.replace('%user_id%',user_id)).first();
					if (!user_elem.html()) {
						return false;
					} else {
						if (user_elem.hasClass('offline')) {
							user_elem.removeClass('offline').addClass('online');
							user_elem.attr('data-is-online', 'true');
						}
						return true;
					}
				} catch(e) {
					return false;
				}
			},
			setUserOffline: function(user_id){
				var self = this;
				var user_elem;

				try {
					user_elem = self.mainWidgetContentBodyContent.find('.channel-user[data-id="%user_id%"]'.replace('%user_id%',user_id)).first();
					if (!user_elem.html()) {
						return false;
					} else {
						if (!user_elem.hasClass('offline')) {
							user_elem.removeClass('online').addClass('offline');
							user_elem.attr('data-is-online', 'false');
							// user_elem.attr('ts', 0);
						}

						return true;
					}
				} catch(e) {
					return false;
				}
			},
			mainWidgetUpdateUser: function(user_id, user_name, user_image){
				var self = this;
				var user_elem = self.mainWidgetContentBodyContent.find('.channel-user[data-id="%user_id%"]'.replace('%user_id%',user_id)).first();

				if (user_name) {
					user_elem.attr('data-name', user_name);
					user_elem.find('.user-name').html(user_name);
				}

				if (user_image) {
					//user_elem.find('.user-picture-img').attr('src', user_image);
				}
			},
			mainWidgetGetUser: function(user_id){
				var self = this;
				var user_elem = self.mainWidgetContentBodyContent.find('.channel-user[data-id="%user_id%"]'.replace('%user_id%',user_id)).first();

				//if (!user_elem.attr('data-name') && !user_elem.find('.user-picture-img').attr('src')) {
				if (!user_elem.attr('data-name')) {
					return false;
				}

				return {
					user_name: user_elem.attr('data-name'),
					//user_image: user_elem.find('.user-picture-img').attr('src'),
				};
			},
			mainWidgetAddUser: function(user_id, user_name, user_picture, unread_count, is_online){
				var self = this;
				//var block = '<div class="channel-user offline" data-is-online="%is_online%" data-id="%user_id%" data-name="%user_name%"><div class="user-picture"><img class="user-picture-img" src="%user_picture%" /></div><div class="user-unreadcount">%unread_count%</div><div class="user-name">%user_name%</div></div>';
				var videoblock = '<span class="pull-right"><i class="fa fa-volume-up"></i><i class="fa fa-video-camera"></i></span>';
				var block = '<li class="channel-user offline" data-is-online="%is_online%" data-id="%user_id%" data-name="%user_name%">'+videoblock+'<div class="user-unreadcount">%unread_count%</div><div class="user-name">%user_name%</div></li>';
				
				if (is_online) {
				    block = '<li class="channel-user" data-is-online="%is_online%" data-id="%user_id%" data-name="%user_name%">'+videoblock+'<div class="user-unreadcount">%unread_count%</div><div class="user-name">%user_name%</div></li>';
				}

				if (!unread_count || parseInt(unread_count) == '0') unread_count = '0';
				block = block.replace('%user_id%', user_id).replace(/%user_name%/g, user_name).replace('%user_picture%', user_picture)
				block = block.replace('%is_online%', is_online).replace('%unread_count%', (unread_count>0) ? unread_count : "");

				self.mainWidgetContentBodyContent.append(block);
				self.setUnreadCount(user_id, unread_count);
			},

			setChatWidgetEventHandler: function(cb){
				var self = this;

				// Chat Widget Keydown
				self.mainWidgetBody.find(".panel-footer").off();
				self.mainWidgetBody.find(".panel-footer").on('keydown', '#btn-input', function(e){
					switch(e.which){
						case 13:
							var text = $(this).val().trim();
							if (!text) break;
							$(this).val('');
							self.chatWidgetSendMessage(text);

							break;
						case 27:
							self.chatWidgetStatus.widgetStatus = widgetStatus.WIDGET_HIDE;
							//ESC do nothing for now.
							break;
						default:
							try {
								if (e.ctrlKey || e.altKey || e.shiftKey || e.metaKey) {
									break;
								}
							} catch(e) {
								break;
							}

							if (self.chatWidgetStatus.typingTimeoutHandler) {
								clearTimeout(self.chatWidgetStatus.typingTimeoutHandler);
								self.chatWidgetStatus.typingTimeoutHandler = false;
							} else {
								// console.log('typeStart called');
								sendbird.typeStart();
							}
							self.chatWidgetStatus.typingTimeoutHandler = setTimeout(function(){
							  sendbird.typeEnd();
								// console.log('typeEnd called');
								clearTimeout(self.chatWidgetStatus.typingTimeoutHandler);
								self.chatWidgetStatus.typingTimeoutHandler = false;
							}, 1000);
					}
				});
				self.mainWidgetBody.find("#btn-chat").on('click',function(){
					var txtinput = self.mainWidgetBody.find("#btn-input");
					var text = txtinput.val().trim();
					if (!text) return false;
					txtinput.val('');
					self.chatWidgetSendMessage(text);
				});

				if (typeof cb == 'function') {
					cb();
				}
			},
			chatWidgetScrollBottom: function(){
				var self = this;
				
				if (self.chatWidgetStatus.scrollHandler) {
					clearTimeout(self.chatWidgetStatus.scrollHandler);
				}

				//console.log(self.chatWidgetContentBody.find(".chat-content").height());
				//console.log(self.chatWidgetContentBodyContent.height());
				//console.log(self.chatWidgetContentBody);

				self.chatWidgetStatus.scrollHandler = setTimeout(function(){
					self.chatWidgetContentBody.animate({scrollTop: self.chatWidgetContentBodyContent.height()}, 100, function(){
						self.chatWidgetStatus.scrollHandler = false;
					});
				}, 100);
			},
			chatWidgetAddMessage: function(type, message, data, prepend){
				var self = this;
				if (!self.chatWidget || !message) {
					return;
				}

				// escape tag string
				message = message.replace(/</g,'&lt;');

				var replaced_message = "";
				switch(type){
					case "notice":
						replaced_message = "";
						break;
					case "send_message":
						var username = data.user.name;
						var msgts = new Date(parseInt(data.user.msgts));
						msgts = msgts.toLocaleString();
						//replaced_message = "<li class='right clearfix'><div class='send_message'>%username%</div><div class='message'><div class='space'></div><div class='send_message'>%msg%</div></div></li>".replace(/%msg%/, message).replace(/%username%/, username);
						replaced_message = "<li class='right clearfix'><div class='chat-body clearfix'><div class='header'><small class='text-muted'><span class='glyphicon glyphicon-time'></span>%msgts%</small></div><p>%msg%</p></div></li>".replace(/%msg%/, message).replace(/%msgts%/, msgts).replace(/%username%/, username);
						break;
					case "receive_message":
						var username = data.user.name;
						var msgts = new Date(parseInt(data.user.msgts));
						msgts = msgts.toLocaleString();
						//replaced_message = "<li class='left clearfix'><div class='receive_message'>%username%</div><div class='message'><div class='receive_message'>%msg%</div><div class='space'></div></div></li>".replace(/%msg%/, message).replace(/%username%/, username);
						replaced_message = "<li class='left clearfix'><span class='chat-img pull-left'><img class='img-circle' src='//content.copypress.com/js/agora/assets/user.png'></span><div class='chat-body clearfix'><div class='header'><strong class='primary-font'>%username%</strong><small class='pull-right text-muted'><span class='glyphicon glyphicon-time'></span>%msgts%</small></div><p>%msg%</p></div></li>".replace(/%msg%/, message).replace(/%msgts%/, msgts).replace(/%username%/, username);
						break;
				}
				if (prepend) {
					self.chatWidgetContentBodyContent.prepend(replaced_message);
				} else {
					self.chatWidgetContentBodyContent.append(replaced_message);
				}
				self.chatWidgetScrollBottom();
			},

			//---------------------unuseful function start!--------------------//
			showChatWidget: function(cb){
				var self = this;

				if (self.chatWidgetStatus.widgetStatus == widgetStatus.WIDGET_MINIMIZE) {
					self.resizeChatWidget(widgetStatus.WIDGET_OPEN);
				}
				self.chatWidgetStatus.widgetStatus = widgetStatus.WIDGET_OPEN;
				if (typeof cb == 'function') {
					cb();
				}
			},
			resizeChatWidget: function(status, cb) {
				var self = this;
				switch (status) {
					case widgetStatus.WIDGET_MINIMIZE:
						self.chatWidgetStatus.widgetStatus = widgetStatus.WIDGET_MINIMIZE;
						break;
					case widgetStatus.WIDGET_OPEN:
						self.chatWidgetStatus.widgetStatus = widgetStatus.WIDGET_OPEN;
						//##self.chatWidgetSetCaption(self.chatWidgetStatus.chatUserName);
						self.setMarkAsRead();
						// sendbird.markAsRead(self.chatWidgetStatus.chatChannelURL);
    					self.setUnreadCount(self.chatWidgetStatus.chatUserId, 0);
						break;
				}
			},
			//---------------------unuseful function end!--------------------//

			clearChatWidget: function(cb){
				var self = this;

				//console.log('clearChatWidget called');

				//self.mainWidgetSetCaption('Chat');
				//self.chatWidgetContentBody.find('ul.chat').html('');
				self.mainWidgetBody.find('.panel-body ul.chat').html('');
				self.mainWidgetBody.find('#btn-input').val('');
				if (typeof cb == 'function') {
					cb();
				}
			},
			chatWidgetFocus: function(){
				var self = this;
				self.mainWidgetBody.find('#btn-input').focus();
			},
			chatWidgetSendMessage: function(message){
				sendbird.message(message);
			},
			showChatWidgetTyping: function(data){
				var self = this;

				var blink_elem = self.chatWidgetContentBodyContent.find('.blink');
				if (blink_elem.html()) {
					// console.log(blink_elem);
					return;
				}

				var username = data.user.name;
				var message = '...';
				var msgts = new Date();
				msgts = msgts.toLocaleString();
				replaced_message = "<li class='left clearfix blink'><span class='chat-img pull-left'><img class='img-circle' src='//content.copypress.com/js/agora/assets/user.png'></span><div class='chat-body clearfix'><div class='header'><strong class='primary-font'>%username%</strong><small class='pull-right text-muted'><span class='glyphicon glyphicon-time'></span>%msgts%</small></div><p>%msg%</p></div></li>".replace(/%msg%/, message).replace(/%msgts%/, msgts).replace(/%username%/, username);
				//var replaced_message = "<div class='receive_message blink'>%username%</div><div class='message blink'><div class='receive_message'>%msg%</div><div class='space'></div></div>".replace(/%msg%/, message).replace(/%username%/, username);
				self.chatWidgetContentBodyContent.append(replaced_message);
				self.chatWidgetScrollBottom();
			},
			hideChatWidgetTyping: function(data){
				var self = this;
				var blink_elem = self.chatWidgetContentBodyContent.find('.blink');

				if (blink_elem.html()) {
					blink_elem.remove();
				}
			},

			showPanelDropDown: function(){
				var self = this;
				var tooldm = self.mainWidgetBody.find("ul.dropdown-menu");
				self.mainWidgetBody.find(".js-tool-dropdown").off("click").on("click", function(){
					if (tooldm.is(":visible")) {
						tooldm.hide();
					} else {
						tooldm.show();
					}
					if (self.mainWidgetBody.find(".js-min-max-box span").is(".glyphicon-fullscreen")) self.mainWidgetBody.find(".js-min-max-box").click();
				});
			},

			bindEventToPageUser: function(){
				var self = this;
				$(".js-cp-box-user").each(function(){
					$(this).off("click").on("click",function(){
						var user_id = $(this).attr("data-id");
						var user_name = $(this).attr("data-name");
						//console.log($(this).attr("data-name"));
						self.mainWidgetAddUser(user_id, user_name, "", 0, true);

						//###############Add User Into Databases####################################//
						$.ajax({
							'type': 'POST',
							'dataType': 'json',
							'url': self.config.internal_users_url,
							'data':{act:"add",user_id:self.config.user_id,user_name:self.config.user_name,invite_ids:user_id,invite_users:user_name},
							'success':function(data){
								self.mainWidgetScrollTop();
							},
							'error':function(XHR, TS, errMsg){
								console.log(TS);
								console.log(errMsg);
							},
							'complete':function(XHR,TS){XHR = null;}
						});

					});
				});
			},




			/**************************************************************************************************/
			/*                           Agora Codes Start!!!                                                 */
			/**************************************************************************************************/
			agoraWidgetStatus: {
				chatChannel: '',
				chatUserId: '',
				chatUserName: '',
				prevChatChannel: '',
				localStream: '',
				agoraClient: '',
				agoraKey: '35419fdb26d44b1c889e56f44b680067',
				videoDevice: '',
				audioDevice: '',
				videoChecked: true,
				audioChecked: true,
				videoDpi: '720p_1',
				viewSwitch: false,
				remoteStreamList:{},
			},

			initAgoraRTC: function(){
				var self = this;
				var aws = self.agoraWidgetStatus;
				//console.log(aws.agoraKey);
				console.log('Joining channel ' + aws.agoraKey + ' : ' +  aws.chatChannel);
				var client = aws.agoraClient = AgoraRTC.createClient();
				var uid = self.config.user_id;

				aws.agoraClient.init(aws.agoraKey,function () {
					console.log("AgoraRTC client initialized");
					//var token = undefined;
					//console.log(client);
					if (aws.prevChatChannel != '') {
						console.log(aws.prevChatChannel);
						/*
						aws.agoraClient.leave(function(){console.log('leave channel ' + aws.prevChatChannel)}, 
												function(){console.log('leave channel error')});
						*/
					} else {
						aws.prevChatChannel = aws.chatChannel;
					}

					aws.agoraClient.join(aws.chatChannel, aws.agoraKey, function(uid){
						console.log("User " + uid + " join channel successfully");
						aws.localStream = self.initLocalStream(uid);
					},
					function(err) {
						console.log("Join channel failed", err);
					});
				}, function(err){
					console.log("AgoraRTC client init failed", err);
					alert(err);
				});

				self.subscribeAgoraSdkEvents();
				self.subscribeAgoraDomEvents();
			},

			initLocalStream: function(id, callback){
				var self = this;
				var _cfg = self.config;
				var _aws = self.agoraWidgetStatus;
				var uid = id || _cfg.user_id;

				if(_aws.localStream){
					console.log("localStream exists");
					_aws.agoraClient.unpublish(_aws.localStream, function(err){
						console.log("unpublish localStream fail.", err);
					});
					_aws.localStream.close();
				}

				_aws.localStream = AgoraRTC.createStream({
					streamID     : uid,
					audio        : true,
					video        : _aws.videoChecked,
					screen       : false,
					cameraId     : _aws.videoDevice,
					microphoneId : _aws.audioDevice
				});
				if(_aws.videoChecked){
					_aws.localStream.setVideoProfile(_aws.videoDpi);
				}

				_aws.localStream.init(function() {
					console.log("getUserMedia successfully");
					console.log(_aws.localStream);
					if(!_aws.videoChecked){
						self.chatWidgetContentBody.find(".agora-screen .left div").addClass("waitAudio");
						self.chatWidgetContentBody.find("#agora_local div").hide();
						_aws.localStream.play('agora_local', self.config.assets_url);
					} else {
						if(_aws.viewSwitch){
							$("body").append('<div id="agora_remote'+_aws.localStream.getId()+ '"></div>');
							_aws.localStream.play('agora_remote'+ _aws.localStream.getId());

							self.mainWidgetBody.find(".agora-screen .right ul li.remoteVideo").html($("#agora_remote"+_aws.localStream.getId()));
							//self.mainWidgetBody.find(".agora-screen .right ul").html('');
							//self.mainWidgetBody.find(".agora-screen .right ul").append();
						} else {
							$("body").append('<div class="" id="agora_local"></div>');
							_aws.localStream.play("agora_local", self.config.assets_url);
							self.mainWidgetBody.find(".agora-screen .left").html($("#agora_local"));
							self.mainWidgetBody.find(".agora-screen .left video").css({"width":"492px","height":"369px","top":"0px"});
						}
					}

					_aws.agoraClient.publish(_aws.localStream, function (err) {
						console.log("Publish local stream error: " + err);
					});
					//client.on('stream-published', function (evt) {
					//console.log("Publish local stream successfully");
					//});
				},
				function (err){
					console.log("getUserMedia failed", err);
				});
				return _aws.localStream;
			},

			subscribeAgoraSdkEvents: function() {
				var self = this;
				var _aws = self.agoraWidgetStatus;

				_aws.agoraClient.on('stream-added', function (evt) {
					var stream = evt.stream;
					console.log("New stream added: " + stream.getId());
					//alert("New stream added: " + stream.getId());
					console.log("Subscribe ", stream);
					_aws.agoraClient.subscribe(stream, function (err) {
						console.log("Subscribe stream failed", err);
					});
				});

				_aws.agoraClient.on('peer-leave', function(evt){
					var stream = evt.stream;
					console.log(stream);
					//alert("peer-leave: " + evt.uid);
					//var $p = $('<p id="infoStream' + evt.uid + '">' + evt.uid + ' quit from room</p>');
					//$(".info").append($p);
					//setTimeout(function(){$p.remove();}, 10*1000);
					delete _aws.remoteStreamList[evt.uid];
					stream.stop();
					//console.log($("#agora_remote" + evt.uid).length);
					//if($("#agora_remote" + evt.uid).length > 0){
					//	$("#agora_remote" + evt.uid).parent().remove();
					//}
				});

				_aws.agoraClient.on('stream-removed', function(evt){
					var stream = evt.stream;
					//alert("stream-removed: " + evt.uid);
					//var $p = $('<p id="infoStream' + stream.getId() + '">' + stream.getId() + ' quit from room</p>');
					//$(".info").append($p);
					//setTimeout(function(){$p.remove();}, 10*1000);
					delete _aws.remoteStreamList[stream.getId()];
					stream.stop();
					//if($("#agora_remote" + stream.getId()).length > 0){
					//	$("#agora_remote" + stream.getId()).parent().remove();
					//}
				});


				_aws.agoraClient.on('stream-subscribed', function (evt) {
					var stream = evt.stream;
					console.log("Catch stream-subscribed event");
					console.log("Subscribe remote stream successfully: " + stream.getId());
					console.log(evt);
					//alert("stream-subscribed: " + evt.uid);
					//self.displayInfo(stream);
					/*
					if (_aws.remoteStreamList.length>0) {
						$.each(_aws.remoteStreamList, function(i, stv){
							stv.stop();
						});
					}
					*/
					_aws.remoteStreamList[stream.getId()] = stream;
					//console.log("--------------------------------------------------------------------");
					//console.log(_aws.remoteStreamList);
					members = 0;
					for(var key in _aws.remoteStreamList){
						members += 1 ;
						if (key != stream.getId()) _aws.remoteStreamList[key].close();
					}
					//if (members>1) alert("more than two people");

					self.mainWidgetBody.find(".agora-content").hide();
					//##members == 1 ? self.timedCount(): null;
					var $container = _aws.viewSwitch ? self.mainWidgetBody.find(".agora-screen .left ul") : self.mainWidgetBody.find(".agora-screen .right ul");

					if(!_aws.videoChecked){
						self.mainWidgetBody.find(".agora-screen").removeClass("agora-wait").addClass("audio");
						if ($container.find("li.remoteAudio").length>0){
							$container.find("li.remoteAudio").html('<p>'+ stream.getId() + '</p></li>');
						} else {
							$container.append('<li class="remoteAudio"><p>'+ stream.getId() + '</p></li>');
						}
						//$container.append('<li class="remoteAudio"><p>'+ stream.getId() + '</p></li>');

						//$container.append('<li class="remoteAudio"><p>'+ stream.getId() + '</p></li>');
						$("body").append('<div id="agora_remote'+stream.getId()+ '"></div>');
						stream.play('agora_remote'+stream.getId());
						$container.find("li.remoteAudio").prepend($("#agora_remote"+stream.getId()));
						$container.find("li.remoteAudio #agora_remote" + stream.getId() + " div").hide();
						return;
					}
					if(members == 1){
						self.mainWidgetBody.find(".agora-screen").removeClass("agora-wait").addClass("video single");
					} else {
						self.mainWidgetBody.find(".agora-screen").removeClass("agora-wait single").addClass("video");
						_aws.viewSwitch ? null : self.mainWidgetBody.find(".agora-screen").addClass("multi");
					}
					if(stream.video){
						if ($container.find("li.remoteVideo").length>0){
							$container.find("li.remoteVideo").html("");
						} else {
							$container.append('<li class="remoteVideo"></li>');
						}

						//$container.append('<li class="remoteVideo"></li>');
						$("body").append('<div id="agora_remote'+stream.getId()+ '"></div>');
						stream.play('agora_remote'+stream.getId());
						$container.find("li.remoteVideo").html($("#agora_remote"+stream.getId()));
						self.mainWidgetBody.find(".viewSwitch").click();
					} else {
						if ($container.find("li.remoteAudio").length>0){
							$container.find("li.remoteAudio").html('<p>'+ stream.getId() + '</p></li>');
						} else {
							$container.append('<li class="remoteAudio"><p>'+ stream.getId() + '</p></li>');
						}

						//$container.append('<li class="remoteAudio"><p>'+ stream.getId() + '</p></li>');

						$("body").append('<div id="agora_remote'+stream.getId()+ '"></div>');
						stream.play('agora_remote'+stream.getId());
						$container.find("li.remoteAudio").prepend($("#agora_remote"+stream.getId()));
						$container.find("li.remoteAudio #agora_remote" + stream.getId() + " div").hide();
						//$container.append('<li class="remoteAudio"><div class="audioImg" id="agora_remote'+stream.getId()+ '"></div><p>'+ stream.getId() + '</p></li>');
						//$("#agora_remote" + stream.getId() + " div").hide();
					}
					//##stream.play('agora_remote'+stream.getId());
				});
			},

			subscribeAgoraDomEvents: function() {
				var self = this;
				var _aws = self.agoraWidgetStatus;
				self.mainWidgetBody.find(".agora-screen .leave").on('click', function(){
					_aws.agoraClient.leave();
					_aws.localStream.close();
					self.mainWidgetContents.find("ul.channel .channel-user.active").click();
					//window.location.href = ".";
				});

				/* mute/unmute audio */
				self.mainWidgetBody.find(".agora-screen .audioSwitch").on("click", function(e){
					_aws.disableAudio = !_aws.disableAudio;
					if(_aws.disableAudio){
						_aws.localStream.disableAudio();
						self.mainWidgetBody.find(".agora-screen .audioSwitch div").removeClass("on").addClass("off");
						self.mainWidgetBody.find(".agora-screen .audioSwitch").attr("title", "Enabled audio");
					}
					else{
						_aws.localStream.enableAudio();
						self.mainWidgetBody.find(".agora-screen .audioSwitch div").removeClass("off").addClass("on");
						self.mainWidgetBody.find(".agora-screen .audioSwitch").attr("title", "Mute");
					}
				});

				/* Camera on/off */
				self.mainWidgetBody.find(".agora-screen .videoSwitch").on("click", function(e){
					_aws.disableVideo = !_aws.disableVideo;
					if(_aws.disableVideo){
						_aws.localStream.disableVideo();
						self.mainWidgetBody.find(".agora-screen .videoSwitch div").removeClass("on").addClass("off");
						self.mainWidgetBody.find(".agora-screen .videoSwitch").attr("title", "Unmute camera");
					}
					else{
						_aws.localStream.enableVideo();
						self.mainWidgetBody.find(".agora-screen .videoSwitch div").removeClass("off").addClass("on");
						self.mainWidgetBody.find(".agora-screen .videoSwitch p").attr("title", "Camera Off");
					}
				});

				/*
				self.mainWidgetBody.find(".agora-screen .left, .agora-screen .right").on('click', function(e){
					if (self.mainWidgetBody.find(".agora-screen .left").is(".col-xs-8")){
						self.mainWidgetBody.find(".agora-screen .left").removeClass("col-xs-8").addClass("col-xs-4");
						self.mainWidgetBody.find(".agora-screen .right").removeClass("col-xs-4").addClass("col-xs-8");
					} else {
						self.mainWidgetBody.find(".agora-screen .left").removeClass("col-xs-4").addClass("col-xs-8");
						self.mainWidgetBody.find(".agora-screen .right").removeClass("col-xs-8").addClass("col-xs-4");
					}
				});
				*/

				/* Switch View */
				self.mainWidgetBody.find(".viewSwitch").on('click', function(e){
					_aws.viewSwitch = !_aws.viewSwitch;

					if (_aws.viewSwitch){
						self.mainWidgetBody.find(".agora-screen .left").addClass("small-window");
						self.mainWidgetBody.find(".agora-screen .agora-content").removeClass("small-window");
						self.mainWidgetBody.find(".agora-screen .right").removeClass("small-window");
						self.mainWidgetBody.find(".agora-screen .left video").css({"width":"180px","height":"150px","top":"0px"});
						self.mainWidgetBody.find(".agora-screen .right video").css({"width":"492px","height":"369px","top":"0px"});
					} else {
						self.mainWidgetBody.find(".agora-screen .left").removeClass("small-window");
						self.mainWidgetBody.find(".agora-screen .agora-content").addClass("small-window");
						self.mainWidgetBody.find(".agora-screen .right").addClass("small-window");
						self.mainWidgetBody.find(".agora-screen .left video").css({"width":"492px","height":"369px","top":"0px"});
						self.mainWidgetBody.find(".agora-screen .right video").css({"width":"180px","height":"180px","top":"0px"});
					}
					/*
					if (self.mainWidgetBody.find(".agora-screen .left").is(".col-xs-8")){
						self.mainWidgetBody.find(".agora-screen .left").removeClass("col-xs-8").addClass("col-xs-4");
						self.mainWidgetBody.find(".agora-screen .right").removeClass("col-xs-4").addClass("col-xs-8");
					} else {
						self.mainWidgetBody.find(".agora-screen .left").removeClass("col-xs-4").addClass("col-xs-8");
						self.mainWidgetBody.find(".agora-screen .right").removeClass("col-xs-8").addClass("col-xs-4");
					}
					*/

					/*
					if(_aws.viewSwitch){
						self.mainWidgetBody.find(".viewSwitch div").removeClass("on").addClass("off");
						self.mainWidgetBody.find(".agora-screen").attr("class", "agora-screen video switch");
						self.mainWidgetBody.find(".agora-screen .left").html('<ul></ul>');
						$container = self.mainWidgetBody.find(".agora-screen .left ul");
						self.mainWidgetBody.find(".agora-screen .right ul").html("");
						console.log('localStream', _aws.localStream);
						if(_aws.localStream.video){
							self.mainWidgetBody.find(".right ul").append('<li class="remoteVideo"><div id="agora_remote'+ _aws.localStream.getId()+ '"></div></li>');
						} else {
							self.mainWidgetBody.find(".right ul").append('<li class="remoteAudio"><div class="audioImg" id="agora_remote'+ _aws.localStream.getId()+ '"></div><p>'+ _aws.localStream.getId() + '</p></li>');
						}
						_aws.localStream.play('agora_remote'+ localStream.getId());
					} else {
						self.mainWidgetBody.find(".viewSwitch div").removeClass("off").addClass("on");
						self.mainWidgetBody.find(".agora-screen").removeClass("switch");
						self.mainWidgetBody.find(".agora-screen").addClass(members > 1? "multi": "single");
						$container = $(".agora-screen .right ul");
						self.mainWidgetBody.find(".agora-screen .left").html('<div class="" id="local"></div>');
						_aws.localStream.play("local");
					}
					$container.html("");
					for(var key in _aws.remoteStremList){
						var stream = _aws.remoteStremList[key];
						if(stream.video){
							$container.append('<li class="remoteVideo"><div id="agora_remote'+stream.getId()+ '"></div></li>');
						}
						else{
							$container.append('<li class="remoteAudio"><div class="audioImg" id="agora_remote'+stream.getId()+ '"></div><p>'+ stream.getId() + '</p></li>');
						}
						stream.play('agora_remote'+stream.getId());
					}
					*/
				});
			},

			agoraCalleeNotice: function(user_id, type, last_call_ts) {
				var self = this;

				var user_elem = self.mainWidgetContentBodyContent.find('.channel-user[data-id="%user_id%"]'.replace('%user_id%',user_id)).first();
				if (type == 'video') {
					self.agoraWidgetStatus.videoChecked = true;
					user_elem.find('span i.fa-video-camera').addClass("notice");
				} else {
					self.agoraWidgetStatus.videoChecked = false;
					user_elem.find('span i.fa-volume-up').addClass("notice");
				}

				if (last_call_ts) {
					user_elem.attr('data-last-agora-ts', last_call_ts);
				}
			},

			updateAgoraCalleeNotice: function() {
				var self = this;
				$.ajax({
					'type': 'GET',
					'dataType': 'json',
					//'url': "js/agorachannels.json?user_id="+self.config.user_id,
					'url': self.config.calllogs_url,
					'data': 'uid='+self.config.user_id+"&act=get",
					'success':function(data){
						for (var i in data['channels']) {
							var user = data['channels'][i];
							//console.log(user.caller,self.config.user_id,user.callee);
							user.agora_caller = "user" + user.agora_caller;
							user.agora_callee = "user" + user.agora_callee;
							if (user.agora_caller == self.config.user_id || user.agora_callee != self.config.user_id) {
								continue;
							}

							var last_call_ts = Date.parse(new Date(user.created));
							last_call_ts = last_call_ts / 1000;
							self.agoraCalleeNotice(user.agora_caller, user.type, last_call_ts);
						}
					},
					'error':function(XHR, TS, errMsg){
						console.log(TS);
						console.log(errMsg);
					},
					'complete':function(XHR,TS){XHR = null;}
				});
			},

			addAgoraCallerLog: function() {
				var self = this;
				var grandpaele = $(this).parent().parent();
				var target_user_id = self.agoraWidgetStatus.chatUserId;
				$.ajax({
					'type': 'POST',
					'dataType': 'json',
					//'url': "js/agorachannels.json?user_id="+self.config.user_id,
					'url': self.config.calllogs_url,
					'data':{act:"add",agora_caller:self.config.user_id,agora_callee:target_user_id,type:"video"},
					'success':function(data){
						//we can do time count here. so far, we just do nothing;
					},
					'error':function(XHR, TS, errMsg){
						console.log(TS);
						console.log(errMsg);
					},
					'complete':function(XHR,TS){XHR = null;}
				});
			},

			/*
			displayInfo: function(stream) {
				var $p = $('<p id="infoStream' + stream.getId() + '">' + stream.getId() + ' joined the room</p>');
				$(".info").append($p);
				setTimeout(function(){$p.remove();}, 10*1000);
			},

			timedCount: function() {
				var c = 0;
				var t;
				setInterval(function(){
					hour = parseInt(c / 3600);// hours
					min = parseInt(c / 60);// minutes
					if(min>=60){min=min%60}
					lastsecs = c % 60;
					$(".telephone").html( hour + ":" + min + ":" + lastsecs )
					c=c+1;
				},1000);
			},
			*/

			getAudioAndVideoContent: function() {
				navigator.getUserMedia || (navigator.getUserMedia = navigator.mozGetUserMedia ||  navigator.webkitGetUserMedia || navigator.msGetUserMedia);

				if (navigator.getUserMedia) {
					//do something
					navigator.getUserMedia({video: true,audio: true}, function(stream) {
						console.log(stream);
						// Do something with the stream.
					},function(err){
						console.log(stream);
					});
				} else {
					console.log('your browser not support getUserMedia');
				}
			},
			/**************************************************************************************************/
			/*                             Agora Codes End!!!                                                 */
			/**************************************************************************************************/

		};


		// Get Script File Domain
		var jsscript = document.getElementsByTagName("script"); 
		for (var i = 0; i < jsscript.length; i++) { 
			var pattern = /chatbox\.js/i; // the name of your js, whose source you are looking for
			if ( pattern.test( jsscript[i].getAttribute("src") ) ) {
				var match_data = jsscript[i].getAttribute("src").match(/^(.+)(chatbox.js)(.*)$/);

				if (match_data) {
					widget.config.scriptURL = match_data[1];
					widget.config.user_id = jsscript[i].getAttribute("data-user-id");
					widget.config.user_name = jsscript[i].getAttribute("data-user-name");
					widget.config.app_id = jsscript[i].getAttribute("data-app-id");
					widget.config.access_token = jsscript[i].getAttribute("data-access-token");
					widget.config.image_url = jsscript[i].getAttribute("data-image-url");
				} else {
					console.log('can\'t found widget url');
					widget.config.scriptURL = '';
				}
				// console.log("widget url: "+widget.config.scriptURL);
			}
		};

		loadScript(widget.config.scriptURL+"bootstrap.min.js", function(){});

		// Import SendBird SDK
		loadScript(widget.config.scriptURL+"SendBird.min.js", function(){
			setTimeout(function(){
				widget.init();
			}, 2000);

			//widget.init();
		});

		loadScript(widget.config.scriptURL+"socket.io.js", function(){
			loadScript(widget.config.scriptURL+"adapter.js", function(){
				loadScript(widget.config.scriptURL+"AgoraRTCSDK-1.2.5.js", function(){
				//##loadScript("//rtcsdk.agora.io/AgoraRTCSDK-1.2.0.js", function(){
					AgoraRTC.getDevices(function(devices){
						//###AgoraRTC.View({"url":"//content.copypress.com/js/agora"});
						//agoraWidget.bindEventToChannelList();
						//console.log(devices);
					});
				});
			});
		});
		
		function loadScript(sScriptSrc, oCallback) {
			var oHead = document.getElementsByTagName('head')[0];
			var oScript = document.createElement('script');
			oScript.type = 'text/javascript';
			oScript.src = sScriptSrc;
			oScript.onload = oCallback;
			oHead.appendChild(oScript);
		}
	});
})(jQuery);
