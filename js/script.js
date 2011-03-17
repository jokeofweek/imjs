var content, updateTimer;
var messageForm, messageText;
var conversations = [], conversationList, currentConversation = 0;
var contactList;

/*
 * Helper function which will go fetch the source
 * element of an event while being cross-browser compatible.
 */
function getSource(obj){
    return (obj.srcElement) ? obj.srcElement : obj.target;
}

/*
 * Function which is called when an existing conversation is 
 * clicked.
 */
function clickConversation(obj){
    // Fix srcElement for Firefox (target)
    selectConversation(getSource(obj).name);
}

/*
 * Function which is called when a contact is clicked, therefore
 * creating a new conversation with them or focusing on any
 * existong one.
 */
function startConversation(obj){
    // Fix srcElement for Firefox (target)
    var source = getSource(obj);
    var id = source.name;
    var username = source.innerHTML;
    
    // If the conversation already exists focus on it, or else create it
    if (conversations[id] === undefined) {
        getConversation(id, username);
        selectConversation(id);
    } else
        selectConversation(id);
}

function closeConversation(obj){
    // Fix srcElement for Firefox (target)
    var id = getSource(obj).name;

    selectConversation(0);
    
    $('#li-conv-' + id).fadeOut("slow",function(){$(this).remove();});
    $('#content #conv-'+id).remove();
    conversations[id] = undefined;
}

/*
 * Function which returns the conversation screen object
 * for a given ID. Note that if the conversation does not already exist,
 * it will be created.
 *
 * Note: The username parameter is optional and is simply for setting
 *       up the conversation link
 */
 function getConversation(id, username){

    if (conversations[id] === undefined){  
        var adder = '<li class="tab-link" id="li-conv-' + id + '"><a href="#" name="' + id + '" id="select-conv-' + id + '">' + username + '</a></li>';

        // Add to conversation list
        conversationList.append(adder);
        $('#select-conv-' + id).click(clickConversation);

        // Add conversation space
        content.append('<p id="conv-' + id + '"></p>');
        conversations[id] = $('#content #conv-' + id).addClass('conversation');
    }

    return conversations[id];
}

// Function which sets a specific conversation id as the currently selected conversation
function selectConversation(id){
    if (id === currentConversation)
        return;

    if (conversations[id] !== undefined){
        if (conversations[currentConversation] !== undefined){
            conversations[currentConversation].css('visibility', 'hidden');
        }

        if (currentConversation == '0' && id != '0')
            messageForm.css('visibility', 'visible');
        else if (currentConversation != '0' && id == '0')
            messageForm.css('visibility', 'hidden');

        conversations[id].css('visibility', 'visible');
        currentConversation = id;
    
    }
}

// Function which will populate the contact list with fetched information
function setupContactList(data){
    
    // Setup is a conversation tab
    conversations[0] = $('#conv-0');
    conversations[0].css('visibility', 'visible');
    $('#contacts-link').click(clickConversation);

    // Initialize the list of contacts
    contactList = $('#contact-list');
    conversations[0].append(contactList);

    for (node in data){
        contactList.append('<li id="li-contact-' + data[node].i + '"><a href="#"  name="' + data[node].i + '" id="start-conv-' + data[node].i + '">' + data[node].u + '</a></li>');
        $('#start-conv-' + data[node].i).click(startConversation);
    }   
}

/*
 * Function which initializes all the script objects and timers,
 * along with any required processing which must be done at
 * the beginning. 
 */
$(document).ready(function(){
    // Cache objects
    content = $("#content");
    messageForm = $('#message-form');
    messageText = $('#message-form input[name=m]');
    conversationList = $('#conversation-list');

    // Setup the contact list
    $.getJSON('contacts.php', setupContactList);

    /*
     * Timer which fetches new messages from the server every
     * interval of 500 milliseconds, and then adds it to the
     * appropriate conversation.
     */
    updateTimer = window.setInterval( 
        function(){
            $.post('fetch.php', '',  function(data){
                    if (data === '1') {
                        location.reload(true);
                    } else if (data != '') {
                        data = eval(data);
                        for (var i=0; i<data.length; i++){
                            // Add contacts who haven't messaged until now
                           getConversation(data[i].s, data[i].u).append(data[i].u + ': ' + data[i].m + '<br/>'); 
                        }
                    }
                   
            })
        }, 
    500);

    messageForm.submit(function(){
        if (messageText.val() !== ''){
            $.post('send.php', messageForm.serialize() + '&r=' + currentConversation, function(data){
                if (data == '2'){
                    conversations[currentConversation].append('You: ' + messageText.val() + '<br/>');
                    messageText.val('');
                } else if (data == '1') {
                    location.reload(true); // Received refresh signal
                }
            });
        }
        
        return false;
    });
});
