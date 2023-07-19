@extends('layouts.layoutChat')

@section('content')

<div class="content-wrapper">
  <div class="container">

    <div class="chat mt-5 mb-0">
        <div class="chatbox__header bg-info">
            <div class="chatbox__image--header">
                <img src="{{ asset('/tools/dist/img/user2-160x160.jpg') }}" alt="image">
            </div>
            <div class="chatbox__content--header">
                <h4 class="chatbox__heading--header">Choose person to chat</h4>
                {{-- <p class="chatbox__description--header">There are many variations of passages of Lorem Ipsum available</p> --}}
            </div>
        </div>
    
        <div class="chatbox__messages overflow-hidden" id="msgContainer">
            <div>
              <div class="row">
                <div class="col-6">
                </div>
                <div class="col-6">
                  <img src="{{ asset('/tools/chat/images/chatPage.png') }}" class="img-fluid" alt="">
                </div>
              </div>
            </div>
        </div>

        <form>
            <div class="chatbox__footer">
                <input type="text" placeholder="Write a message...">
                <i class="fa-solid fa-paper-plane text-white"></i>
            </div>
        </form>

    </div>      

  </div>
</div>

    
@endsection