@extends('layouts.app')

@section('content')
<div class="mb-24">
    <div>
        @include('top-nav')
        @include('student.main-nav')
    </div>
    <div class="terms container">
        @include('student.mobi-sidebar')
        <div class="mt-12 card">
            <div class="card-header">Terms & Conditions</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <ol>
                            <li>
                                <h3>Terms of Use</h3>
                                <p>
                                    Your access to this website implies that you are bound by the Terms of Use of this
                                    website as stated below.
                                </p>
                            </li>
                            <li>
                                <h3>No Warranties</h3>
                                <p>
                                    Although mDarasa pro attempts to provide accurate information, names, images,
                                    pictures, logos, icons, documents, and materials (collectively, the "Contents") on
                                    the Website, it makes no representation, endorsement, or warranty that such Contents
                                    are accurate or suitable for any particular purpose. The website and its contents
                                    are provided on an "as is" basis. Use of the website and its contents is at the
                                    user's own risk. The website and its content are provided without any
                                    representations, endorsements, or warranties of any kind whatsoever, either express
                                    or implied, including, but not limited to, any warranties of title or accuracy and
                                    any implied warranties of merchantability, fitness for a particular purpose, or
                                    non-infringement, with the sole exception of warranties (if any) which cannot be
                                    expressly excluded under applicable law. As noted below, mDarasa pro also makes no
                                    representations, endorsements, or warranties, either express or implied, with
                                    respect to any website operated by a third party and accessible through the links on
                                    this site. mDarasa pro also makes no representations, endorsements, or warranties,
                                    either express or implied, with respect to any content provided by our clients for
                                    use by the users and accessible through the links on any of mDarasa pro services.
                                </p>
                            </li>
                            <li>
                                <h3>Limited Liability</h3>
                                <p>
                                    In any event, mDarasa pro would not be liable for any damages, including, without
                                    limitation, indirect, incidental, special, consequential or punitive damages,
                                    whether under a contract, tort or any other theory of liability, arising in
                                    connection with any party's use of the website or service or in connection with any
                                    failure of performance, error, interruption, defect, delay in operation or
                                    transmission, computer virus, line system failure, loss of data, or loss of use
                                    related to this website or any website operated by any third party or any contents
                                    of this website or any other website, even if mDarasa pro is aware of the
                                    possibility of such damages
                                </p>
                            </li>
                            <li>
                                <h3>Copyrights and Other Intellectual Property</h3>
                                <p>
                                    Unless otherwise noted, all the contents of this website, including white papers,
                                    case studies, graphics, icons and overall appearance of the Website are the sole and
                                    exclusive property of mDarasa pro or the content authors.
                                </p>
                                <p>
                                    mDarasa pro reserves all the rights to change, modify and alter any or all of the
                                    content on this website without any notice. Organizations that use mDarasa pro
                                    services reserves all the rights to change, modify and alter any or all of the
                                    content. mDarasa pro is not responsible for informing the users about any change or
                                    modification in any event.
                                </p>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
