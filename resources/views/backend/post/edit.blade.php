@extends('backend.layouts.general')
@section('title', __('labels.backend.access.post.management') . ' | ' . __('labels.backend.access.post.edit'))
@section('content')
    <div class="card">

        <div class="card-header">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                        @lang('labels.backend.access.post.management')
                        <small class="text-muted">@lang('labels.backend.access.post.edit')</small>
                    </h4>
                </div><!--col-->
            </div>
        </div>
        {{-- Header Card --}}

        <div class="card-body">
            <div class="row">

                <div class="col-sm-12">
                    <form class="edit-post" id="post_add_edit" method="POST" action={{route('admin.posts.edit')}} enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$post['id']}}">
                        <div class="form-group">
                            <label for="post_name">@lang('labels.backend.access.post.table.title')</label>

                            <input
                                type="text"
                                class="form-control"
                                id="title"
                                name="title"
                                placeholder="@lang('labels.backend.access.post.table.title')"
                                value="{{ old('title', $post['title'])  }}" />
                        </div>

                        @error('title')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        {{-- Title --}}


                        <div class="form-group">
                            <label for="post_name">@lang('labels.backend.access.post.table.the_excerpt')</label>
                            <textarea type="text" class="form-control" id="the_excerpt" name="the_excerpt" placeholder="@lang('labels.backend.access.post.table.the_excerpt')">{{ old('the_excerpt' , $post['the_excerpt']) }}</textarea>



                        </div>

                        @error('the_excerpt')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        {{-- Excerpt --}}


                        @if (sizeof($categories) > 0)
                            <div class="form-group">
                                <label for="cat_id">@lang('labels.backend.access.post.table.category')</label>
                                <select
                                    title="@lang('labels.backend.access.post.table.category')"
                                    data-live-search="true"
                                    multiple
                                    class="form-control selectpicker" id="cat_id"
                                    name="cat_id[]">
                                    @foreach ($categories as $key => $category)
                                        <option
                                            {{ in_array($category->id, $oldCategories) ? 'selected' : null }}
                                            value="{{$category->id}}">name:{{$category->name}} - slug: {{$category->slug}}
                                        </option>

                                    @endforeach
                                </select>
                            </div>
                        @endif
                        {{-- Categories --}}

                        @if (sizeof($tags) > 0)
                            <div class="form-group">
                                <label for="tag_id">@lang('labels.backend.access.post.table.tag')</label>
                                <select
                                    title="@lang('labels.backend.access.post.table.tag')"
                                    data-live-search="true"
                                    multiple
                                    class="form-control selectpicker" id="tag_id"
                                    name="tag_id[]">
                                    @foreach ($tags as $key => $tag)

                                        <option
                                            {{ in_array($tag->id, $oldTags) ? 'selected' : null }}
                                            value="{{$tag->id}}">{{$tag->tag_name}}
                                        </option>

                                    @endforeach
                                </select>
                            </div>
                        @endif
                        {{-- Categories --}}




                        <ul class="nav nav-tabs nav-custom" role="tablist">
                            <li class="nav-item">

                                <a class="nav-link {{ ($post['type_thumb'] == 'image') ? 'active' : '' }}" href="#image_thumbnail" role="tab" data-toggle="tab">@lang('labels.backend.access.post.table.thumbnail')</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ ($post['type_thumb'] == 'video') ? 'active' : '' }}" href="#video_thumbnail" role="tab" data-toggle="tab">@lang('labels.backend.access.post.table.video')</a>
                            </li>
                        </ul>
                        {{-- Video Or Image --}}

                        <div class="form-group">
                            <input type="hidden" value="{{$post['type_thumb']}}" name="type_thumb" id="type_thumb" />

                            <div id="image_thumbnail" role="tabpanel" class="tab-pane fade in {{ ($post['type_thumb'] == 'image') ? 'active show' : '' }}">

                                <input type="hidden" class="form-control" id="thumbnail" name="thumbnail" placeholder="@lang('labels.backend.access.post.table.thumbnail')" value="{{ old('thumbnail',$post['thumbnail']) }}">

                                <div class="dz-clickable dz-message dz-preview dz-image-preview needsclick">
                                    <i class="fa fa-camera" aria-hidden="true"></i>
                                    <div class="dropzone-previews dropzone"></div>
                                </div>

                                <a href="javascript:;" type="button"  data-toggle="modal" data-target="#imagelistModal">
                                    @lang('labels.backend.access.post.thumbnailTitle')
                                </a>
                                @if ($post->getThumbnail != null)
                                    <input type="hidden" value="{{env('APP_URL').$post->getThumbnail->getUrl('thumb')}}" id="thumbnailEditImage" >
                                @endif


                                <div class="preview_image"></div>
                            </div>
                            {{-- Image --}}

                            <div id="video_thumbnail" role="tabpanel" class="tab-pane fade {{ ($post['type_thumb'] == 'video') ? 'active show' : '' }}">
                                <input type="text" class="form-control" id="video" name="video" placeholder="@lang('labels.backend.access.post.table.video')" value="{{ old('video' , $post['video']) }}">

                                <div class="preview_video"></div>
                            </div>
                            {{-- Video --}}

                        </div>
                        {{-- Thumnail --}}



                        <div class="form-group">
                            <label for="editor">@lang('labels.backend.access.post.table.content')</label>
                            <textarea name="content" id="editor">
                                {{ $post['content'] }}
                            </textarea>
                        </div>
                        {{-- Text Edittor --}}


                        <button type="submit" class="btn btn-primary">@lang('buttons.general.submit')</button>
                    </form>
                    {{-- Form/ --}}
                </div>
                {{-- Cover Col 12 --}}
            </div>
            {{-- Row --}}
        </div>
        {{-- Body Card --}}
    </div>
    {{-- Card /  --}}

    <div class="list-image-modal">
        <div class="modal fade" id="imagelistModal" tabindex="-1" role="dialog" aria-labelledby="imagelistModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="imagelistModalLabel">@lang('labels.backend.access.post.modal_title')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                @include('backend.includes.modal_list_image')
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('buttons.general.cancel')</button>
              </div>
            </div>
          </div>
        </div>
    </div>
    {{-- List Image Modal --}}



@endsection
