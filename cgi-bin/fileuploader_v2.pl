#!/usr/bin/perl
    use strict;
    use CGI::Carp qw(fatalsToBrowser);
    use Digest::MD5;
    my $upload_root = '/var/www/';
    
    my $maxFileSize = 2*1024 * 1024 * 1024; # 1/2mb max file size...
    use CGI;
    my $IN = new CGI;
    my $file = $IN->param('POSTDATA');
    #my $upload_dir = $IN->param('upload_dir');
    
    #my $uploaded_file = $IN->param('qqfile');

    my $upload_dir;
    my $upload_path;
    my $name = Digest::MD5::md5_base64( rand );
   
   $name =~ s/\+/_/g;
   $name =~ s/\//_/g;
    my $type;

    my $qqfile;
    my $get_value;
    my $get_name;
    my $file_ext;
    my $file_name;
    my %get;
    if (length ($ENV{'QUERY_STRING'}) > 0){
        my $buffer = $ENV{'QUERY_STRING'};
        my @pairs = split(/&/, $buffer);
        foreach my $pair(@pairs){
            ($get_name, $get_value) = split(/=/, $pair);
            #$get_value =~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C", hex($1))/eg;
	    $get_value =~ tr/+/ /;
            $get_value =~ s/%([\dA-Fa-f][\dA-Fa-f])/ pack ("C",hex ($1))/eg;

            $get{$get_name} = $get_value;
        }
    }





    $qqfile = $get{"qqfile"};
    $upload_dir = $get{"upload_dir"};
    $upload_path = "$upload_root$upload_dir";
    ($file_name, $file_ext) = split(/\.([^\.]+)$/, $qqfile);
    $type = $file_ext;

    if (!$type) {
        print qq|{ "success": false, "error": "Invalid file type..." }|;
        print STDERR "file has been NOT been uploaded... \n";
    }
    print STDERR "Making dir: $upload_root/$upload_dir \n";

    mkdir("$upload_path");

    open(WRITEIT, ">$upload_path/$name.$type") or die "Cant write to $upload_path/$name.$type. Reason: $!";
        print WRITEIT $file;
    close(WRITEIT);

    my $check_size = -s "$upload_path/$name.$type";

    print STDERR qq|Main filesize: $check_size  Max Filesize: $maxFileSize \n\n|;

    print $IN->header();
    if ($check_size < 1) {
       # print STDERR "ooops, its empty - gonna get rid of it!\n";
        print qq|{ "success": false, "error": "File is empty..." }|;
       # print STDERR "file has been NOT been uploaded... \n";
    } elsif ($check_size > $maxFileSize) {
        #print STDERR "ooops, its too large - gonna get rid of it!\n";
        print qq|{ "success": false, "error": "File is too large..." }|;
        #print STDERR "file has been NOT been uploaded... \n";
    } else  {
        print qq|{ "success": true,"resume_file": "$name.$file_ext","upload_path": "$upload_path"}|;
#         print qq|{ "success": true,"resume_file": "$name.$file_ext"}|;

       # print STDERR "file has been successfully uploaded... thank you.\n";
    }
