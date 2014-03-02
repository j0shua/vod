\documentclass[24pt]{article}
\usepackage{anysize}
\marginsize{6cm}{6cm}{0cm}{0cm}

\headheight 0cm
\headsep 0cm
\XeTeXlinebreaklocale "th"
\XeTeXlinebreakskip = 0pt plus 1pt %
%\oddsidemargin 0cm
%\evensidemargin 0cm
\usepackage{amsmath,amssymb,amsfonts}
%\usepackage{mathtools}
%%%
%%% 1\qquad = 0.93 cm
%%%
\pagestyle{empty}
\usepackage{empheq}
\usepackage{amsmath} % จะใช้ package ของ math อื่นใด ให้เรียกก่อน fontspec
\usepackage{amssymb} % จะใช้ package ของ math อื่นใด ให้เรียกก่อน fontspec
\usepackage{amsfonts} % จะใช้ package ของ math อื่นใด ให้เรียกก่อน fontspec


%%%% เพิ่มสำหรับ math font
\usepackage[sc]{mathpazo}
%\linespread{1.05}         % Palatino needs more leading (space between lines)
\usepackage[T1]{fontenc}
%%%% จบ

\usepackage{mathspec} % เรียกใช้แค่นี้มีค่า = \usepackage[no-math]{fontspec}\usepackage{mathspec}
\usepackage{xunicode,xltxtra}
\XeTeXlinebreaklocale "th"
\XeTeXlinebreakskip = 0pt plus 1pt %
%\defaultfontfeatures{Scale=1.23}
\defaultfontfeatures{Scale=1.5}
%\renewcommand{\baselinestretch}{1.2}
\renewcommand{\baselinestretch}{1.3}
%\setmainfont{NiramitAS}
\setmainfont[Scale=1.6]{TH Niramit AS}
%\newfontfamily\kodchasal{TH Kodchasal} % ตั้งชื่อฟอนต์ใหม่เพื่อให้ง่ายต่อการใช้งาน เผื่อว่าในเอกสารต้องการให้มีหลายฟอนต์ เวลาใช้ก็ {\examplefont ข้อความต่าง ๆ}
%\usepackage[xetex,cmyk,x11names,svgnames,dvips*]{xcolor}
%\usepackage[xetex,rgb,x11names,svgnames,dvips]{xcolor} % -- dvips,svgnames มีชื่อสีซ้ำกัน เลือกอันใดอันหนึ่ง
\usepackage[xetex,cmyk,x11names,svgnames]{xcolor}
%\definecolor{MyColor}{rgb}{0.3,0.4,0.5} % กำหนดสี และชื่อที่จะใช้เรียกสีโดย xcolor
\everymath{\displaystyle} % บังคับให้ทุกสมการเป็น displaystyle
% --------------- เปลี่ยน Chapter --> บทที่ ,วันที่ภาษาไทย -------------------
\usepackage{polyglossia}
%\setdefaultlanguage{thai} %ใส่แล้วลูกศรใน tikz เพี้ยนหมดเลย
%\newfontfamily{\thaifont}[Script=thai]{TH Sarabun New}
%\newfontfamily{\thaifontsf}[Script=thai]{TH Sarabun New} %สำหรับ tikz Calendar
% ---------------------------------------------------------
% --------- Dummy Text -----------
\usepackage{lipsum}
\usepackage{blindtext}
% --------- End Dummy Text -------
\usepackage{adjustbox}
\usepackage{marginnote}
\usepackage{paralist}
\usepackage{enumitem,xcolor} % ใช้ rusume ได้ ไม่ต้อง setcounter
\usepackage{tikz}

\usetikzlibrary{arrows,shadings,calc,shapes,backgrounds,positioning,fit,petri,intersections,through,scopes}
\usetikzlibrary{decorations.text,decorations.pathmorphing,calendar,decorations.pathreplacing,decorations.markings}
%\usetikzlibrary{positioning,arrows}
%\usetikzlibrary{patterns}

%\usepackage{tkz-euclide} %http://tex.stackexchange.com/questions/96459/tikz-create-right-angle-triangle-with-angle-labels
%\usetkzobj{all}
% 
% จะใช้ tabular ไม่ได้ สัสสสส...

\usepackage{pgfplots}

\usepackage{tabls}
\usepackage{graphicx}
\usepackage{array}
\usepackage{tabularx}
\usepackage{booktabs}
\usepackage{longtable}
\usepackage{colortbl}
\usepackage{wrapfig} %ตัวหนังสือล้อมรอบรูปหรือตารางได้ (wrapfigure,wraptable) *** ไม่สามารถอยู่ในพวก enumerate ได้
%\usepackage[Glenn]{fncychap}

% ----------------- Font พวก chapter,section -----------------------------
\usepackage{sectsty,ulem}
%\allsectionsfont{\ulemheading{\uline}} % -- ชื่อ section ขีดเส้นใต้ -----------
\setcounter{secnumdepth}{3} % ให้ counter ของพวก section ลงไปถึง subsubsection
\sectionfont{\color{NavyBlue}}
\subsectionfont{\color{Indigo}}
\subsubsectionfont{\color{Indigo}}

% ---------------------- print the section numbering in the margins -----------------------------
%\makeatletter
%\def\@seccntformat#1{\csname the#1\endcsname)\quad} 
%\makeatother
% -----------------------------------------------------------------------------------------------

\usepackage{eso-pic,picture}

\usepackage{cellspace}
\usepackage[usenames,dvipsnames]{pstricks} 
\usepackage{epsfig} 
\usepackage{pst-grad} % For gradients
\usepackage{pst-plot} % For axes 
\usepackage{makecell}
\usepackage{fancyhdr}
\usepackage{lastpage}
\usepackage{fancybox}
\usepackage{multirow}
\usepackage{calc}
\usepackage{multicol}
\usepackage{multienum}
\usepackage{framed}
\usepackage[framemethod=tikz]{mdframed}
%\usepackage{chemfig}
%\usepackage{mhchem}
%\usepackage{xparse}
\usepackage[customcolors]{hf-tikz}
%--------------------- กล่องข้อความสวย ๆ ----------------
\usepackage{tcolorbox}
\tcbuselibrary{documentation,skins}
% -----------------------------------------------------
%\usepackage[top=1in,bottom=1in,outer=2in,inner=1.2in,heightrounded,marginparwidth=1in,marginparsep=.7in,showframe]{geometry}
%\usepackage[bottom=5cm,right=1.2in,showframe]{geometry}
%\usepackage[bottom=5cm,right=1.2in]{geometry}
%\usepackage[bottom=1.5cm,top=1.5cm,left=1.5cm,right=1.5cm]{geometry}
\usepackage[bottom=0cm,top=0cm,left=6cm,right=1cm]{geometry} %for exam
% --------- แก้ปัญหา Command \clipbox already defined. หลังจากเพิ่ม adjustbox
% http://www.tex.ac.uk/cgi-bin/texfaq2html?label=alreadydef
%\usepackage{savesym}
%\savesymbol{clipbox}
% ----------------------------------------------------------------------
%\usepackage{adjustbox}
%\restoresymbol{clipbox}

%%%%%%%% GNU PLOT For WINDOWS
%%% http://tex.stackexchange.com/questions/45366/adding-gnuplot-capability-to-latex
\usepackage{gnuplottex}          % <- Use if running MiKTeX.
%\usepackage[miktex]{gnuplottex} % <- Use instead if running TeX Live.

\usepackage{etoolbox}

% http://tex.stackexchange.com/questions/110018/professional-slashbox-alternative/110264#110264
% ทำเส้นทะแยงหัวตาราง
\usepackage{diagbox}

% ระยะช่องว่างหน้า+หลังเลขหรืออักษรในสมการ หน่วยเป็น mmu , 1 mmu = 1mu/1000 , 18mu = 1 em ,default = 500 mmu = 1/36 em 
% ตัวไหนจะให้มีระยะพิเศษก็ใส่ " นำหน้า เช่น $ x^{"2} หรือใส่ทีละคำให้ใส่เป็น $ x^{\"3yz"} $
% \setminwhitespace[XXXX] 
\setminwhitespace[3000] 

% เลือก Math Font ต่าง ๆ นา ๆ
%\setmathsfont(Digits,Latin){Asana Math}
%\setmathsfont(Digits,Latin){jsMath-cmr10}
%\setmathsfont(Digits,Latin){Kerkis}
%\setmathsfont(Digits,Latin){Neo Euler}
%\setmathsfont(Digits,Latin){Fontin}
%\setmathsfont(Digits,Latin){Plakken}
%\setmathsfont(Digits,Latin){DejaVu Serif}
%\setmathsfont(Digits,Latin){STIXGeneral}
%\setmathsfont(Digits,Latin){CMU Bright}
%\setmathsfont(Digits,Latin){Iwona Light}
%\setmathsfont(Digits,Latin,Greek)[Numbers={Lining,Proportional}]{Iwona Light}
%\setmathsfont(Digits,Latin,Greek){TH Sarabun New}
%\setmathsfont(Digits,Latin,Greek){Mathmos Original}
% ใช้ฟอนต์ OTF ในเอกสารแต่ MATH ใช้ฟอนต์ Math
%\usepackage[no-math]{fontspec}

% ใช้ฟอนต์ OTF ในเอกสารและ MATH ใช้ฟอนต์ OTF
%\usepackage[no-math]{fontspec}
%\usepackage{mathspec}
%\setmainfont{TH Sarabun New}
%\setallmainfonts(Digits,Latin,Greek){TH Sarabun New}
%\setallmainfonts(Digits,Latin,Greek){TH Sarabun New}

% \setmathrm จะเปลี่ยนฟอนต์เฉพาะที่อยู่ในคำสั่ง \mathrm{.....} เท่านั้น
%\setmathrm{TH Sarabun New}
%\setmathsfont(Digits,Latin,Greek){TH Sarabun New}
%\setmathfont(Digits,Latin,Greek){TH Sarabun New}

\definecolor{BLACK}{cmyk}{0,0,0,1}
\definecolor{darkblue}{cmyk}{1.00, 0.50, 0.00, 0.40}
\definecolor{cblue}{cmyk}{0.57,0.01,0,0}
%\color{darkblue}
%\color{BLACK}
\makeatletter
\AtBeginDocument{\color{BLACK}\global\let\default@color\current@color}
\makeatother


\newcommand*\circled[1]{\tikz[baseline=(char.base)]{%
            \node[shape=circle,fill=blue!20,inner sep=2pt] (char) {#1};}}
\newcommand*\circledd[1]{\tikz[baseline=(char.base)]{%
            \node[shape=rectangle,draw=red,inner sep=4pt] (char) {$#1$};}}

%\setlist[enumerate,1]{leftmargin=*,resume}% ตั้งให้ enumerate level 1 ไม่ indent และต่อข้ออัตโนมัติ โดยไม่ต้องใช้ counter
%\setlist[enumerate,1]{leftmargin=*,resume,label=\color{blue}\theenumi}% ใส่สีให้ด้วย
%\setlist[enumerate,1]{leftmargin=*,resume,label=\protect\circled{\arabic*}} % วงกลมที่ตัวเลข enumerate , %parsep=-.1cm,
\setlist[itemize,1]{topsep=0cm,itemsep=0cm}
\setlist[description,1]{leftmargin=0cm,itemsep=0cm,topsep=0cm}

\newcommand{\nonet}{\textbf{(แนว O-NET) }}
\newcommand{\ncmu}{\textbf{(แนว มช.) }}
\newcommand{\cmu}[1]{\textbf{(มช. #1)}}
\newcommand{\onet}[1]{\textbf{(O-NET #1)}}
\newcommand{\ans}[1]{\textbf{ตอบข้อ #1)} \\ \textbf{แนวคิด}}
\newcommand{\aans}[1]{\textbf{ตอบ #1} \\ \textbf{แนวคิด}}
\newcommand{\aaans}[1]{\textbf{ตอบ #1)} \\} 
\newcommand{\sol}{\textbf{วิธีทำ }}


% ------------------------------------ โจทย์ + รูป + Choice environment ---------------------------------------------------------------------------------------------------
\newenvironment{ljrp} { \begin{minipage}[t]{.65\linewidth} } { \end{minipage} }
\newenvironment{ljrp2} { \begin{minipage}[t]{.5\linewidth} } { \end{minipage} }
\newenvironment{ljrp3} { \begin{minipage}[t]{.75\linewidth} } { \end{minipage} }
\newenvironment{ljrp5} { \begin{minipage}[t]{.7\linewidth} } { \end{minipage} }
\newenvironment{ljrp6} { \begin{minipage}[t]{.45\linewidth} } { \end{minipage} }
\newenvironment{ljrp7} { \begin{minipage}[t]{.55\linewidth} } { \end{minipage} }
\newenvironment{ljrp8} { \begin{minipage}[t]{.3\linewidth} } { \end{minipage} }
\newenvironment{ljrp9} { \begin{minipage}[t]{.6\linewidth} } { \end{minipage} }
\newenvironment{solve} { \begin{minipage}[t]{.9\linewidth} } { \end{minipage} }
\newenvironment{rp}[1] { \hfill \begin{adjustbox}{valign=t} \begin{minipage}[t]{.3\linewidth} \includegraphics[width=\textwidth]{#1} } { \end{minipage} \end{adjustbox} }
\newenvironment{rpx} { \hfill \begin{adjustbox}{valign=t} \begin{minipage}[t]{.3\linewidth}  } { \end{minipage} \end{adjustbox} }
\newenvironment{rp2x} { \hfill \begin{adjustbox}{valign=t} \begin{minipage}[t]{.45\linewidth}  } { \end{minipage} \end{adjustbox} }
\newenvironment{rp3x} { \hfill \begin{adjustbox}{valign=t} \begin{minipage}[t]{.2\linewidth}   } { \end{minipage} \end{adjustbox} }
\newenvironment{rp6x} { \hfill \begin{adjustbox}{valign=t} \begin{minipage}[t]{.5\linewidth}  } { \end{minipage} \end{adjustbox} }
\newenvironment{rp7x} { \hfill \begin{adjustbox}{valign=t} \begin{minipage}[t]{.4\linewidth}  } { \end{minipage} \end{adjustbox} }
\newenvironment{rp8x} { \hfill \begin{adjustbox}{valign=t} \begin{minipage}[t]{.65\linewidth}  } { \end{minipage} \end{adjustbox} }
\newenvironment{rp9x} { \hfill \begin{adjustbox}{valign=t} \begin{minipage}[t]{.35\linewidth}  } { \end{minipage} \end{adjustbox} }


\newenvironment{rp2}[1] { \hfill \begin{adjustbox}{valign=t} \begin{minipage}[t]{.45\linewidth} \includegraphics[width=\textwidth]{#1} } { \end{minipage} \end{adjustbox} }
\newenvironment{rp3}[1] { \hfill \begin{adjustbox}{valign=t} \begin{minipage}[t]{.2\linewidth} \includegraphics[width=\textwidth]{#1} } { \end{minipage} \end{adjustbox} }
\newenvironment{rp4}[1] { \hfill \begin{adjustbox}{valign=t} \begin{minipage}[t]{.1\linewidth} \includegraphics[width=\textwidth]{#1} } { \end{minipage} \end{adjustbox} }
\newenvironment{rp5}[1] { \hfill \begin{adjustbox}{valign=t} \begin{minipage}[t]{.25\linewidth} \includegraphics[width=\textwidth]{#1} } { \end{minipage} \end{adjustbox} }

\newenvironment{rjlp} { \hfill \begin{minipage}[t]{.65\linewidth} } { \end{minipage} }
\newenvironment{lp}[1] { \begin{adjustbox}{valign=t} \begin{minipage}[t]{.3\linewidth} \includegraphics[width=\textwidth]{#1} } { \end{minipage} \end{adjustbox} }
\newenvironment{lpx} { \hfill \begin{adjustbox}{valign=t} \begin{minipage}[t]{.3\linewidth}  } { \end{minipage} \end{adjustbox} }


\newenvironment{rjlp2} { \hfill \begin{minipage}[t]{.6\linewidth} } { \end{minipage} }
\newenvironment{lp2}[1] { \begin{adjustbox}{valign=t} \begin{minipage}[t]{.35\linewidth} \includegraphics[width=\textwidth]{#1} } { \end{minipage} \end{adjustbox} }
\newenvironment{lp2x} { \hfill \begin{adjustbox}{valign=t} \begin{minipage}[t]{.35\linewidth}  } { \end{minipage} \end{adjustbox} }

\newenvironment{rjlp3} { \hfill \begin{minipage}[t]{.7\linewidth} } { \end{minipage} }
\newenvironment{lp3}[1] { \begin{adjustbox}{valign=t} \begin{minipage}[t]{.25\linewidth} \includegraphics[width=\textwidth]{#1} } { \end{minipage} \end{adjustbox} }

\newenvironment{rjlp4} { \hfill \begin{minipage}[t]{.55\linewidth} } { \end{minipage} }
\newenvironment{lp4}[1] { \begin{adjustbox}{valign=t} \begin{minipage}[t]{.4\linewidth} \includegraphics[width=\textwidth]{#1} } { \end{minipage} \end{adjustbox} }
\newenvironment{lp4x} { \hfill \begin{adjustbox}{valign=t} \begin{minipage}[t]{.4\linewidth}  } { \end{minipage} \end{adjustbox} }


% เพิ่ม vspace{-5pt} ก่อน mitemxxx เพื่อลดขนาดบรรทัด
% หรือเพิ่มก่อน choice environment
% \setlength{\labelwidth}{8pt} ที่ \defaultfontfeatures{Scale=1.23}
\newenvironment{4c}[4] { \begin{multienumerate} \setlength{\labelwidth}{10pt} \setlength{\labelsep}{4pt} \mitemxxxx{#1}{#2}{#3}{#4}} { \end{multienumerate}}
\newenvironment{2c}[4] { \begin{multienumerate} \setlength{\labelwidth}{10pt} \setlength{\labelsep}{4pt}\setlength{\itemsep}{0pt} \mitemxx{#1}{#2} \mitemxx{#3}{#4}} { \end{multienumerate}}
\newenvironment{1c}[4] { 
    \begin{multienumerate} 
    \setlength{\labelwidth}{10pt} 
    \setlength{\itemsep}{0pt} 
    \setlength{\labelsep}{4pt}
    %\setlength{\parsep}{0pt} 
    %\setlength{\topsep}{0pt} 
    %\setlength{\partopsep}{0pt} 
    %\setlength{\parskip}{0pt} 
    \mitemx{#1} \mitemx{#2} \mitemx{#3} \mitemx{#4}
} { \end{multienumerate} }
% -----------------------------------------------------------------------------------------------------------------------------------------------------------------------

%%%%% ----------- พิมพ์รหัสท้ายข้อ ----------------------------------------------
\newcommand\mybox[2][]{\tikz[overlay]\node[fill=blue!20,inner sep=2pt, anchor=text, rectangle, rounded corners=1mm,#1] {#2};\phantom{#2}}
\usepackage{fmtcount}
\usepackage{xifthen,changepage}
\newif\ifrunjc % define a logical variable ชื่อ runjc
\runjctrue % กำหนดให้ runjc เป็น true --> พิมพ์รหัสที่ข้อ + เนื้อหา
%\runjcfalse % กำหนดให้ runjc เป็น false --> ไม่พิมพ์รหัสที่ข้อ + เนื้อหา
\newcounter{runj} % กำหนด counter ใหม่สำหรับ jote ชื่อ runj
\setcounter{runj}{1} % กำหนดค่าเริ่มต้น counter
\newcounter{runc} % กำหนด counter ใหม่สำหรับ content ชื่อ runc
\setcounter{runc}{1} % กำหนดค่าเริ่มต้น counter
\newcommand{\runningj}{% กำหนดคำสั่งพิมพ์รหัสท้ายโจทย์ชื่อ \runningj
    \ifrunjc
    	%\GoF{TH Sarabun New}
        \mybox{M1\padzeroes[4]{\decimal{runj}}}\stepcounter{runj}
        %\hfill \mybox{P1\padzeroes[4]{\decimal{runj}}}\stepcounter{runj}
        % \hfill \mybox[fill=red!20]{p1\padzeroes[4]{\decimal{runj}}}\stepcounter{runj} % เปลี่ยนสีเป็นชมพู
        %\GoF{Palatino nova W02 Light}
    \fi
}
\newcommand{\runningc}{% กำหนดคำสั่งพิมพ์รหัสท้ายเนื้อหาชื่อ \runningc
    \ifrunjc
        % \ovalbox{p2\padzeroes[4]{\decimal{runc}}}\stepcounter{runc}
        % \hfill\mybox{\textbf{P2\padzeroes[4]{\decimal{runc}}}}\stepcounter{runc}
        \noindent $\triangleright\triangleright$ \textbf{M2\padzeroes[4]{\decimal{runc}}}\stepcounter{runc}
    \fi
}
%%%% ----------------------------------------------------------------------

% -------- Environment for Content --------------------
\newenvironment{c1}%
{\begin{Sbox}\begin{minipage}}%
{\end{minipage}\end{Sbox}\begin{center}\fbox{\TheSbox}\end{center}}
%%%%%% -Usage-  \begin{c1}{.9\textwidth}\input{content-3.tex}\end{c1} %%%%%%%
\newenvironment{c2}%
{\begin{tcolorbox}[title=\runningc,colback=white]}
{\end{tcolorbox}}
\newenvironment{c3}%
%{\begin{tcolorbox}[title=\runningc,skin=widget,colback=Wheat!50!white,colframe=FireBrick!75!black]}
{\begin{tcolorbox}[title=\runningc,coltext=BLACK,colback=LightYellow1,colframe=OrangeRed4]}
{\end{tcolorbox}}
\newenvironment{c4}
{\begin{tcolorbox}}
{\end{tcolorbox}}
\newenvironment{c5}%
%{\begin{tcolorbox}[title=\runningc,skin=widget,colback=Wheat!50!white,colframe=FireBrick!75!black]}
{\begin{tcolorbox}[colback=LightYellow1,colframe=OrangeRed4]}
{\end{tcolorbox}}
%%%%%% -Usage-  \begin{c2}\input{content-3.tex}\end{c2} %%%%%%%

% -------- Environment for Jote ----------------------
%\newenvironment{jote}
%{\begin{enumerate}[resume] \item \runningj}
%{\end{enumerate}}

%\usepackage{anyfontsize}
%\usepackage{lmodern}  
\usepackage{moresize} % เพิ่ม \HUGE , \ssmall
\def\CHshift#1{\raisebox{2pt}}


\newfontfamily\niramit{TH Niramit AS}
%\newfontfamily\droid{Droid erif}
%\newfontfamily\nanum{NanumGothic}
%\newfontfamily\maven{Maven Pro Light}
%\newfontfamily\blanch{Blanch Condensed}
%\newfontfamily\blanchI{Blanch Condensed Inline}
%\newfontfamily\blanchCI{Blanch Caps Light}
%\newfontfamily\blanchL{Blanch Condensed Light}
%\newfontfamily\blanchC{Blanch Caps}
%\newfontfamily\blanchCI{Blanch Caps Inline}
%\newfontfamily\codeL{Code Light}
%\newfontfamily\codeB{Code Bold}
%\newfontfamily\fabrica{Fabrica}
%\newfontfamily\scifly{SciFly}
%\newfontfamily\sket{Sketchetik}
%\newfontfamily\akhanake{Book_Akhanake}
%\newfontfamily\sarang{Layiji SaRangHeYo OT}
%\newfontfamily\sig{Signika}
%\newfontfamily\quark{Quark}
%\newfontfamily\fontcraft{Fontcraft}

% ขยาย,ลด ขนาด font ในสมการ
\newcommand*{\Scale}[2][4]{\scalebox{#1}{$#2$}}%
\newcommand*{\Resize}[2]{\resizebox{#1}{!}{$#2$}}%

% สำหรับลากลูกศรใน บทที่ 4 , conntent 1
\newcommand{\tikzmark}[1]{\tikz[overlay,remember picture] \node (#1) {};}
\newcommand{\DrawBox}[2]{%
  \begin{tikzpicture}[overlay,remember picture]
    %\draw[->,shorten >=5pt,shorten <=5pt,out=0,in=330,distance=.5cm,#1] (MarkA.south) to (MarkC.north west);
    %\draw[->,shorten >=5pt,shorten <=5pt,out=50,in=140,distance=0.3cm,#2] (MarkB.north) to (MarkC.south west);
    \draw[->,thick,#1] (MarkA.south) to ([xshift=-3mm,yshift=3mm]MarkC.north east);
    \draw[->,thick,bend left,#2] ([yshift=1mm]MarkB.north) to  ([xshift=-3mm,yshift=2mm]MarkC.south east);
  \end{tikzpicture}
}
\newcommand{\tikzmarkk}[1]{\tikz[overlay,remember picture] \node (#1) {};}
\tikzset{lowline/.style={to path={-- ++(0,-.15) -| (\tikztotarget)}}}

\newcommand{\strike}{
	\tikz[overlay,remember picture] \draw[thick,red,xshift=1mm,yshift=1.5mm] (-.3,-.3)--(.3,.3) (-.3,.3)--(.3,-.3) ;
}

% put color to \boxed math command
\newcommand*{\Mboxcolor}{orange}
\makeatletter
\newcommand{\Mboxed}[1]{\textcolor{\Mboxcolor}{%
\tikz[baseline={([yshift=-1ex]current bounding box.center)}] \node [rectangle, minimum width=1ex,rounded corners,draw] {\normalcolor\m@th$\displaystyle#1$};}}
\makeatother

%ใช้สำหรับเฉลยแบบฝึกหัดบทที่ 3 ข้อ 15 ข้อเดียว
%\setmathrm{Droid Serif}

\definecolor{tri}{cmyk}{0.2,0.2,0.01,0}
\newcommand{\ftri}{
\tikz{
\draw[fill=OldLace,color=tri] (0,0) -- (0,0.5) -- (0.5,0.25) -- (0,0);
}}

\newcommand{\EndSec}{
\vspace{1cm}
\begin{center}
\begin{tikzpicture}
		%\draw[fill=tri,color=tri] (0,0) rectangle (0.4,0.5);
		%\draw[fill=tri,color=tri] (10,0) rectangle (10.4,0.5);
		\draw[line width=1mm,color=tri] (0,0.4) -- (10.4,0.4);
\end{tikzpicture}
\end{center}
}
\newcommand{\EndToc}{
\vspace{1cm}
\begin{center}
\begin{tikzpicture}
		%\draw[fill=tri,color=tri] (0,0) rectangle (0.4,0.5);
		%\draw[fill=tri,color=tri] (10,0) rectangle (10.4,0.5);
		\draw[line width=1mm,color=DarkSlateGray] (0,0.4) -- (10.4,0.4);
\end{tikzpicture}
\end{center}
}
\newcommand{\SolM}{
\begin{center}
\begin{tikzpicture}
		%\filldraw [draw=OldLace,fill=OldLace] (0,0) rectangle (\paperwidth,4cm);
		%\node[font=\fontsize{38}{46},anchor=center] at (10.5,0) {\quark\textbf{\color{DarkSlateGray} เฉลยโจทย์ประกอบเนื้อหา}};
		\draw[fill=tri,color=tri] (0,0) rectangle (0.4,0.5);
		\node[font=\fontsize{29}{37},anchor=south west] at (0.5,0) {\quark\textbf{\color{DarkSlateGray} เฉลยโจทย์ประกอบเนื้อหา}};
		\draw[fill=tri,color=tri] (13.1,0) rectangle (13.5,0.5);
		\draw[line width=1mm,color=tri] (0,0) -- (13.5,0);
\end{tikzpicture}
\end{center}
}
\newcommand{\SolE}{
\begin{center}
\begin{tikzpicture}
		%\filldraw [draw=OldLace,fill=OldLace] (0,0) rectangle (\paperwidth,4cm);
		%\node[font=\fontsize{38}{46},anchor=center] at (10.5,0) {\quark\textbf{\color{DarkSlateGray} เฉลยแบบฝึกหัด}};
		\draw[fill=tri,color=tri] (0,0) rectangle (0.4,0.5);
		\node[font=\fontsize{26}{34},anchor=south west] at (0.5,0) {\quark\textbf{\color{DarkSlateGray} เฉลยโจทย์เพิ่มเติม}};
		\draw[fill=tri,color=tri] (8.6,0) rectangle (9,0.5);
		\draw[line width=1mm,color=tri] (0,0) -- (9,0);		
\end{tikzpicture}
\end{center}
}

%\newcommand{\exercise}{
%\begin{center}
%\begin{tikzpicture}
		%\filldraw [draw=OldLace,fill=OldLace] (0,0) rectangle (\paperwidth,4cm);
		%\node[font=\fontsize{40}{48},anchor=center] at (10.5,0) {\quark\textbf{\color{DarkSlateGray} แบบฝึกหัด}};
%		\draw[fill=tri,color=tri] (0,0) rectangle (0.4,0.5);
%		\node[font=\fontsize{36}{44},anchor=south west] at (0.5,0) {\quark\textbf{\color{DarkSlateGray} แบบฝึกหัด}};
%		\draw[fill=tri,color=tri] (6.1,0) rectangle (6.5,0.5);
%		\draw[line width=1mm,color=tri] (0,0) -- (6.5,0);	
%\end{tikzpicture}
%\end{center}
%}

\newcommand{\exercise}{
\begin{center}
\begin{tikzpicture}
		%\filldraw [draw=OldLace,fill=OldLace] (0,0) rectangle (\paperwidth,4cm);
		%\node[font=\fontsize{40}{48},anchor=center] at (10.5,0) {\quark\textbf{\color{DarkSlateGray} แบบฝึกหัด}};
		\draw[fill=tri,color=tri] (0,0) rectangle (0.4,0.5);
		\node[font=\fontsize{29}{37},anchor=south west] at (0.5,0) {\quark\textbf{\color{DarkSlateGray} โจทย์เพิ่มเติม}};
		\draw[fill=tri,color=tri] (7.2,0) rectangle (7.6,0.5);
		\draw[line width=1mm,color=tri] (0,0) -- (7.6,0);	
\end{tikzpicture}
\end{center}
}

\newcommand*{\yellowhl}[1]{%
  \tikz[baseline=(X.base)] \node[rectangle, fill=yellow, rounded corners, inner sep=1mm] (X) {#1};%
}

\newcommand*{\chl}[1]{%
  \tikz[baseline=(X.base)] \node[rectangle, fill=OrangeRed4, rounded corners, inner sep=1mm,font=\color{white}] (X) {#1};%
}
\newcommand{\pointthis}[2]{
        \tikz[remember picture,baseline]{\node[anchor=base,inner sep=0,outer sep=0]%
        (#1) {\underline{#1}};\node[overlay,rectangle callout,%
        callout relative pointer={(0.2cm,0.7cm)},fill=green!50,text width=2cm] at ($(#1.north)+(-.5cm,-1.4cm)$) {#2};}%
}%

%% เขียนวิธีทำพันธไอออนิก
\newcommand{\Ionic}[2]{%
  \begin{tikzpicture}[overlay,remember picture]
    %\draw[->,thick,#1] (MarkA.south) to (MarkC.north east);
    %\draw[->,thick,bend left,#2] (MarkB.north) to  (MarkC.south east);
    \node[Ttop,above=of #1,xshift=2mm]  {#2};
  \end{tikzpicture}
}
\newcommand{\IonicDraw}[2]{%
  \begin{tikzpicture}[overlay,remember picture]
    \path[->,blue,thick] ([xshift=3mm,yshift=2mm]#1.north east) edge [out= -90, in= -135](#2.south east);
    \path[->,red,thick] ([xshift=3mm,yshift=2mm]#2.north east) edge [out= -120, in= -45](#1.south east);
  \end{tikzpicture}
}
\tikzset{
	Ttop/.style={
			rectangle,
			minimum size=6mm,
			rounded corners=1mm,
			thick,draw=black!50,
			top color=white,
			bottom color=black!20,
			align=center,
			inner sep=1mm,
			node distance = 3mm,
		},
	overlay,
	remember picture,
	font={\footnotesize }
}
%% วิธีใช้
%% $Na$\tikzmark{Na} \quad $+$ $Cl$\tikzmark{Cl} 
%% \Ionic{Na}{รับ 2 e}
%% \Ionic{Cl}{จ่าย 1 e}
%% \IonicDraw{Na}{Cl}


%% สำหรับทำวงเล็บใน chemfig ดูคู่มือ chemfig หน้า 43
\newcommand\setpolymerdelim[2]{\def\delimleft{#1}\def\delimright{#2}}
\def\makebraces[#1,#2]#3#4#5{%
\edef\delimhalfdim{\the\dimexpr(#1+#2)/2}%
\edef\delimvshift{\the\dimexpr(#1-#2)/2}%
\chemmove{%
\node[at=(#4),yshift=(\delimvshift)]
{$\left\delimleft\vrule height\delimhalfdim depth\delimhalfdim
width0pt\right.$};%
\node[at=(#5),yshift=(\delimvshift)]
{$\left.\vrule height\delimhalfdim depth\delimhalfdim
width0pt\right\delimright_{\rlap{$\scriptstyle#3$}}$};}}
%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

%%% กล่องสีสมการ , ใส่กล่องสีในกล่องสี
\def\mycolorbox#1#2{\kern-\fboxsep{\colorbox{#1}{#2}\kern-\fboxsep}}
%%% \mycolorbox{สี}{$สมการ$}
%%% a \mycolorbox{yellow}{$\mycolorbox{blue}{$a^2$}+b^2=c^2$} b
%%% ตัวอย่างการใช้ colorbox
%%% {\fboxset10pt \colorbox{สี}{$สมการ$}}

%%%% กล่องสีขนาดใหญ่
\makeatletter
\NewDocumentCommand{\Colorbox}{O{\dimexpr\linewidth-12\fboxsep} m m}{%
  \colorbox{#2}{\makebox[#1][c]{#3}}}
\makeatother
%%% ตย.การใช้
%%% \begin{center}
%%% {\fboxsep10pt \Colorbox{Cyan}{$a^2+b^2=c^2$}} 
%%% \end{center}

% กรอบรอบ paragraph ใช้กับ \usepackage[framemethod=tikz]{mdframed}
% วิธีใช้
% \begin{qbox} ... \end{qbox}
\newmdenv[innerlinewidth=0.5pt, roundcorner=4pt,linecolor=SandyBrown,innerleftmargin=6pt,
innerrightmargin=6pt,innertopmargin=6pt,innerbottommargin=6pt]{qbox}


% กล่องข้อความใน "คิดเล่น ๆ" 
% วิธีใช้
% \kidbox{\lipsum[1]}
\tikzstyle{abstractbox} = [
	draw=SandyBrown,
	very thick,
	fill=white,
	rectangle,
	inner sep=10pt,
	style=rounded corners,
	drop shadow={
		fill=SandyBrown,
		opacity=.4
	},
	font=\Large\bfseries,
]
\tikzstyle{abstracttitle} =[
	draw=SandyBrown,
	very thick,
	fill=white,
	font=\Huge\bfseries\color{DarkSlateGray},
	right=10pt,
	style=rounded corners,
]
\newcommand{\kidbox}[2][fill=white]{
	\begin{center}
		\begin{tikzpicture}
        	\node [abstractbox, #1] (box) {
        		\begin{minipage}{0.90\linewidth}
					%\setlength{\parindent}{2mm}
					\vspace{.5cm}			
					#2
            		%\footnotesize #2
				\end{minipage}
			};
			\node[abstracttitle] at (box.north west) {คิดเล่น ๆ};
		\end{tikzpicture}
	\end{center}
}

% Definition of circles
\def\firstcircle{(0,0) circle (1.5cm)}
\def\secondcircle{(0:2cm) circle (1.5cm)}
\def\universe{(-2.5,-2.5) rectangle (4.5,2.5)}
\def\uni{(-2.5,-4.5) rectangle (4.5,3.5)}
\def\onlycircle{(1,0) circle (1.5cm)}
\tikzstyle{circle_filled} =[
	fill=blue!20,
	draw=blue!50,
	thick
]
\tikzstyle{circle_outline} =[
	draw=blue!50, 
	thick
]
\tikzstyle{Setbox}=[
	ultra thick,
	blue!50
]
\tikzstyle{r_filled} =[
	fill=red!20,
	draw=red!50,
	thick
]
\tikzstyle{g_filled} =[
	fill=green!20,
	draw=green!50,
	thick
]
\tikzset{
  big arrow/.style={
    decoration={markings,mark=at position 1 with {\arrow[scale=2.5,#1]{>}}},
    >=latex,
    ->,
    postaction={decorate},
    shorten >=0.4pt},
  big arrow/.default=black}


% สำหรับทำ 3 sets
\makeatletter
\def\venn@strip#1#2\venn@STOP{%
  \def\venn@next{#1}%
  \gdef\venn@rest{#2}%
}
\newcommand{\venn}[2]{%
\begin{tikzpicture}[every node/.style={font={\large}}]
\coordinate (A) at (0,0);
\coordinate (B) at (2,0);
\coordinate (C) at (1,-{sqrt(3)});
\coordinate (S-SE) at (4,-4);
%\coordinate (S-NW) at (-2,{sqrt(3)+3});
\coordinate (S-NW) at (-2,2);
  \edef\venn@rest{#100000000}%
  \foreach \i in {0,...,7} {
  \begin{scope}[even odd rule]
    \expandafter\venn@strip\venn@rest\venn@STOP
    \ifnum\venn@next=1\relax
    \pgfmathparse{Mod(\i,2) == 1 ? "(S-SE) rectangle (S-NW)" : ""}
    \path[clip] \pgfmathresult (A) circle[radius=1.5];
    \pgfmathparse{Mod(floor(\i/2),2) == 1 ? "(S-SE) rectangle (S-NW)" : ""}
    \path[clip] \pgfmathresult (B) circle[radius=1.5];
    \pgfmathparse{Mod(floor(\i/4),2) == 1 ? "(S-SE) rectangle (S-NW)" : ""}
    \path[clip] \pgfmathresult (C) circle[radius=1.5];
    \fill[rounded corners,circle_filled] (S-SE) rectangle (S-NW);
    \fi
  \end{scope}
  }
    \draw[ultra thick,circle_outline] (A) circle[radius=1.5];
    \draw[ultra thick,circle_outline] (B) circle[radius=1.5];
    \draw[ultra thick,circle_outline] (C) circle[radius=1.5];
    \node at ($(A)+(120:1cm)$) {$A$}; 
	\node at ($(B)+(60:1cm)$) {$B$};
	\node at ($(C)+(270:1cm)$) {$C$};
    \draw[thick,rounded corners,circle_outline] (S-SE) rectangle (S-NW);
    \node at ($(C)+(270:2cm)$) {#2};
\end{tikzpicture}
}
\makeatother
\newcommand{\allvendiagrams}{
% To generate the lot:
\foreach \j in {0,...,255} {
  \def\venncode{}
  \foreach \k in {0,...,7} {
    \pgfmathparse{Mod(floor(\j/2^\k),2) == 1 ? "\venncode1" : "\venncode0"}
    \global\let\venncode=\pgfmathresult
  }
  \venn{\venncode}
}
}
% \venn{00100010}{$A - B$}


%\XeTeXinterchartokenstate=1
%% ฟอนต์ภาษาอังกฤษและตัวเลขของเอกสาร ใช้ palatino
%\newcommand{\GoF}[1]{
%\newfontfamily{\SetFont}[Scale=1]{#1}
%%\newfontfamily{\chafont}[Scale=1]{Palatino nova W02 Light}
%%%\newfontfamily{\chafont}[Scale=1]{Palatino nova W02 Light Italic}
%
%\newXeTeXintercharclass\DesireFont
%\XeTeXcharclass`\1=\DesireFont
%\XeTeXcharclass`\2=\DesireFont
%\XeTeXcharclass`\3=\DesireFont
%\XeTeXcharclass`\4=\DesireFont
%\XeTeXcharclass`\5=\DesireFont
%\XeTeXcharclass`\6=\DesireFont
%\XeTeXcharclass`\7=\DesireFont
%\XeTeXcharclass`\8=\DesireFont
%\XeTeXcharclass`\9=\DesireFont
%\XeTeXcharclass`\0=\DesireFont
%\XeTeXcharclass`\a=\DesireFont
%\XeTeXcharclass`\b=\DesireFont
%\XeTeXcharclass`\c=\DesireFont
%\XeTeXcharclass`\d=\DesireFont
%\XeTeXcharclass`\e=\DesireFont
%\XeTeXcharclass`\f=\DesireFont
%\XeTeXcharclass`\g=\DesireFont
%\XeTeXcharclass`\h=\DesireFont
%\XeTeXcharclass`\i=\DesireFont
%\XeTeXcharclass`\j=\DesireFont
%\XeTeXcharclass`\k=\DesireFont
%\XeTeXcharclass`\l=\DesireFont
%\XeTeXcharclass`\m=\DesireFont
%\XeTeXcharclass`\n=\DesireFont
%\XeTeXcharclass`\o=\DesireFont
%\XeTeXcharclass`\p=\DesireFont
%\XeTeXcharclass`\q=\DesireFont
%\XeTeXcharclass`\r=\DesireFont
%\XeTeXcharclass`\s=\DesireFont
%\XeTeXcharclass`\t=\DesireFont
%\XeTeXcharclass`\u=\DesireFont
%\XeTeXcharclass`\v=\DesireFont
%\XeTeXcharclass`\w=\DesireFont
%\XeTeXcharclass`\x=\DesireFont
%\XeTeXcharclass`\y=\DesireFont
%\XeTeXcharclass`\z=\DesireFont
%\XeTeXcharclass`\A=\DesireFont
%\XeTeXcharclass`\B=\DesireFont
%\XeTeXcharclass`\C=\DesireFont
%\XeTeXcharclass`\D=\DesireFont
%\XeTeXcharclass`\E=\DesireFont
%\XeTeXcharclass`\F=\DesireFont
%\XeTeXcharclass`\G=\DesireFont
%\XeTeXcharclass`\H=\DesireFont
%\XeTeXcharclass`\I=\DesireFont
%\XeTeXcharclass`\J=\DesireFont
%\XeTeXcharclass`\K=\DesireFont
%\XeTeXcharclass`\L=\DesireFont
%\XeTeXcharclass`\M=\DesireFont
%\XeTeXcharclass`\N=\DesireFont
%\XeTeXcharclass`\O=\DesireFont
%\XeTeXcharclass`\P=\DesireFont
%\XeTeXcharclass`\Q=\DesireFont
%\XeTeXcharclass`\R=\DesireFont
%\XeTeXcharclass`\S=\DesireFont
%\XeTeXcharclass`\T=\DesireFont
%\XeTeXcharclass`\U=\DesireFont
%\XeTeXcharclass`\V=\DesireFont
%\XeTeXcharclass`\W=\DesireFont
%\XeTeXcharclass`\X=\DesireFont
%\XeTeXcharclass`\Y=\DesireFont
%\XeTeXcharclass`\Z=\DesireFont
%
%\XeTeXinterchartoks 0 \DesireFont = {\begingroup\SetFont}
%\XeTeXinterchartoks 255 \DesireFont = {\begingroup\SetFont}
%\XeTeXinterchartoks \DesireFont 0 = {\endgroup}
%\XeTeXinterchartoks \DesireFont 255 = {\endgroup}
%}

%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

%% \Grid{xmin,ymin,xmax,ymax,color}
%% หรือ \Grid{default} --> จะใช้ค่า default
\usepackage{xstring}
\newcommand{\Grid}[1]{%
	\IfStrEq{#1}{default}{%
		\draw [step=0.1, help lines,red!20] (-5,-5) grid (5,5);
		\draw [step=1, help lines,red!40] (-5,-5) grid (5,5);
		\draw [step=5, help lines,red!100] (-5,-5) grid (5,5);
		\foreach \x in {-5,...,5} {\draw node at (\x,-5) [label=below:{\tiny $\x.0$}]{};};
		\foreach \x in {-5,...,5} {\draw node at (\x,5)  [label=above:{\tiny $\x.0$}]{};};
		\foreach \y in {-5,...,5} {\draw node at (-5,\y) [ label=left:{\tiny $\y.0$}]{};};
		\foreach \y in {-5,...,5} {\draw node at (5,\y)  [label=right:{\tiny $\y.0$}]{};};
	}%
	{%
		\StrBefore{#1}{,}[\xmin]
		\StrBetween[1,2]{#1}{,}{,}[\ymin]
		\StrBetween[2,3]{#1}{,}{,}[\xmax]
		\StrBetween[3,4]{#1}{,}{,}[\ymax]
		\StrBehind[4]{#1}{,}[\gcolor]
		\draw [step=0.2, help lines,\gcolor!10] (\xmin,\ymin) grid (\xmax,\ymax);
		\draw [step=1, help lines,\gcolor!40] (\xmin,\ymin) grid (\xmax,\ymax);
		\draw [step=5, help lines,\gcolor!100] (\xmin,\ymin) grid (\xmax,\ymax);
		%\foreach \x in {\xmin,...,\xmax} {\draw node at (\x,\ymin) [label=below:{\tiny $\x$}]{};};
		\foreach \x in {\xmin,...,\xmax} \node[anchor=north] at (\x,\ymin) {\tiny $\x$};
		%\foreach \y in {\ymin,...,\ymax} {\draw node at (\xmin,\y) [label=left:{\tiny $\y$}]{};};
		\foreach \y in {\ymin,...,\ymax} \node[anchor=east] at (\xmin,\y) {\tiny $\y$};
	}
}
\newcommand{\Gridd}[1]{%
	\IfStrEq{#1}{default}{%
		\draw [step=0.1, help lines,red!20] (-5,-5) grid (5,5);
		\draw [step=1, help lines,red!40] (-5,-5) grid (5,5);
		\draw [step=5, help lines,red!100] (-5,-5) grid (5,5);
		\foreach \x in {-5,...,5} {\draw node at (\x,-5) [label=below:{\tiny $\x.0$}]{};};
		\foreach \x in {-5,...,5} {\draw node at (\x,5)  [label=above:{\tiny $\x.0$}]{};};
		\foreach \y in {-5,...,5} {\draw node at (-5,\y) [ label=left:{\tiny $\y.0$}]{};};
		\foreach \y in {-5,...,5} {\draw node at (5,\y)  [label=right:{\tiny $\y.0$}]{};};
	}%
	{%
		\StrBefore{#1}{,}[\xmin]
		\StrBetween[1,2]{#1}{,}{,}[\ymin]
		\StrBetween[2,3]{#1}{,}{,}[\xmax]
		\StrBetween[3,4]{#1}{,}{,}[\ymax]
		\StrBehind[4]{#1}{,}[\gcolor]
		\draw [step=0.5, help lines,\gcolor!10] (\xmin,\ymin) grid (\xmax,\ymax);
		\draw [step=1, help lines,\gcolor!40] (\xmin,\ymin) grid (\xmax,\ymax);
		\draw [step=5, help lines,\gcolor!100] (\xmin,\ymin) grid (\xmax,\ymax);
	}
}

%% \Axis{xmin,ymin,xmax,ymax,color}
%% หรือ \Axis{default} --> จะใช้ค่า default
\newcommand{\Axis}[1]{
	\IfStrEq{#1}{default}
		{%
			\draw [<->,very thick,red!20,>=triangle 45] (-5.8,0) -- (5.8,0);
			\draw [<->,very thick,red!20,>=triangle 45] (0,-5.8) -- (0,5.8);
			\foreach \x in {-5,...,-1,1,2,...,5} \draw[ thick,red!20] (\x, -2pt) -- (\x, 2pt) node[anchor=north] {{\scriptsize $\x$}};
			\foreach \y in {-5,...,-1,1,2,...,5} \draw[ thick,red!20] (-2pt,\y) -- (2pt,\y) node[anchor=east] {{\scriptsize $\y$}};
			\node[anchor=south,red!20] at (5.6,0) {{\large $X$}};
			\node[anchor=west,red!20] at (0,5.6) {{\large $Y$}};
		}%
		{%
			\StrBefore{#1}{,}[\xmin]
			\StrBetween[1,2]{#1}{,}{,}[\ymin]
			\StrBetween[2,3]{#1}{,}{,}[\xmax]
			\StrBetween[3,4]{#1}{,}{,}[\ymax]
			\StrBehind[4]{#1}{,}[\gcolor]
			\draw [<->,very thick,\gcolor,>=triangle 45] (\xmin-0.8,0) -- (\xmax+0.8,0);
			\draw [<->,very thick,\gcolor,>=triangle 45] (0,\ymin-0.8) -- (0,\ymax+0.8);
			\foreach \x in {\xmin,...,-1} \draw[ thick,\gcolor] (\x, -2pt) -- (\x, 2pt) node[anchor=north] {{\scriptsize $\x$}};
			\foreach \x in {1,...,\xmax} \draw[ thick,\gcolor] (\x, -2pt) -- (\x, 2pt) node[anchor=north] {{\scriptsize $\x$}};
			\foreach \y in {\ymin,...,-1} \draw[ thick,\gcolor] (-2pt,\y) -- (2pt,\y) node[anchor=east] {{\scriptsize $\y$}};
			\foreach \y in {1,...,\ymax} \draw[ thick,\gcolor] (-2pt,\y) -- (2pt,\y) node[anchor=east] {{\scriptsize $\y$}};
			\node[xshift=-.3cm,yshift=-.3cm,\gcolor] at (0,0) {{\scriptsize $0$}};
			\node[anchor=south,\gcolor] at (\xmax+0.6,0) {{\large $X$}};
			\node[anchor=west,\gcolor] at (0,\ymax+0.6) {{\large $Y$}};
		}
	}
\newcommand{\Axiss}[1]{
	\IfStrEq{#1}{default}
		{%
			\draw [<->,very thick,red!20,>=triangle 45] (-5.8,0) -- (5.8,0);
			\draw [<->,very thick,red!20,>=triangle 45] (0,-5.8) -- (0,5.8);
			\foreach \x in {-5,...,-1,1,2,...,5} \draw[ thick,red!20] (\x, -2pt) -- (\x, 2pt) node[anchor=north] {{\large $\x$}};
			\foreach \y in {-5,...,-1,1,2,...,5} \draw[ thick,red!20] (-2pt,\y) -- (2pt,\y) node[anchor=east] {{\large $\y$}};
			\node[anchor=south,red!20] at (5.6,0) {{\large $X$}};
			\node[anchor=west,red!20] at (0,5.6) {{\large $Y$}};
		}%
		{%
			\StrBefore{#1}{,}[\xmin]
			\StrBetween[1,2]{#1}{,}{,}[\ymin]
			\StrBetween[2,3]{#1}{,}{,}[\xmax]
			\StrBetween[3,4]{#1}{,}{,}[\ymax]
			\StrBehind[4]{#1}{,}[\gcolor]
			\draw [<->,ultra thick,\gcolor,>=triangle 45] (\xmin-0.8,0) -- (\xmax+0.8,0);
			\draw [<->,ultra thick,\gcolor,>=triangle 45] (0,\ymin-0.8) -- (0,\ymax+0.8);
			\foreach \x in {\xmin,...,-1} \draw[very thick,\gcolor] (\x, -4pt) -- (\x, 4pt) node[anchor=north,yshift=-6pt,xshift=-5pt] {{\large $\x$}};
			\foreach \x in {1,...,\xmax} \draw[very thick,\gcolor] (\x, -4pt) -- (\x, 4pt) node[anchor=north,yshift=-6pt] {{\large $\x$}};
			\foreach \y in {\ymin,...,-1} \draw[very thick,\gcolor] (-4pt,\y) -- (4pt,\y) node[anchor=east,xshift=-6pt] {{\large $\y$}};
			\foreach \y in {1,...,\ymax} \draw[very thick,\gcolor] (-4pt,\y) -- (4pt,\y) node[anchor=east,xshift=-6pt] {{\large $\y$}};
			\node[xshift=-.3cm,yshift=-.3cm,\gcolor] at (0,0) {{\large $0$}};
			\node[anchor=south,\gcolor] at (\xmax+0.6,0) {{\Large $X$}};
			\node[anchor=west,\gcolor] at (0,\ymax+0.6) {{\Large $Y$}};
		}
	}

% สร้างรูป 3D , http://tex.stackexchange.com/questions/42812/3d-bodies-in-tikz
%%%%%%%%\PassOptionsToPackage{dvipsnames,svgnames}{xcolor}     
%%%%%%%%\usepackage{xkeyval,tkz-base}
%%%%%%%%%\usetikzlibrary{arrows,calc}
%%%%%%%% \makeatletter%   
%%%%%%%%% \pgfkeys{
%%%%%%%%% /tkzcone/.cd,
%%%%%%%%% }    
%%%%%%%%
%%%%%%%%\define@cmdkey[TKZ]{ell}{color}{}
%%%%%%%%\define@cmdkey[TKZ]{ell}{shift}{}  
%%%%%%%%\presetkeys[TKZ]{ell}{color = {},shift = 0}{}
%%%%%%%% %  (#2,#3) coordonnée du centre (#4,#5) Ra et Rb 
%%%%%%%%
%%%%%%%%\newcommand*{\ellipseThreeD}[1][]{\tkz@ellipseThreeD[#1]}% 
%%%%%%%%\def\tkz@ellipseThreeD[#1](#2,#3)(#4,#5){%
%%%%%%%%\setkeys[TKZ]{ell}{#1}%
%%%%%%%%  \draw[yshift=\cmdTKZ@ell@shift cm,dashed] (#4,0) arc(0:180:#4 and #5);
%%%%%%%%  \draw[yshift=\cmdTKZ@ell@shift cm ] (-#4,0) arc(180:360:#4 and #5); 
%%%%%%%%  \path[fill=\cmdTKZ@ell@color,opacity=0.5,shade](#2 cm,#3 cm) ellipse (#4 and #5);  
%%%%%%%%}
%%%%%%%%
%%%%%%%%\newcommand*{\sellipseThreeD}[1][]{\tkz@sellipseThreeD[#1]}% 
%%%%%%%%\def\tkz@sellipseThreeD[#1](#2,#3)(#4,#5){%
%%%%%%%%\setkeys[TKZ]{ell}{#1}%
%%%%%%%%  \draw[yshift=\cmdTKZ@ell@shift cm,dashed] (#4,0) arc(0:180:#4 and #5);
%%%%%%%%  \draw[yshift=\cmdTKZ@ell@shift cm ] (-#4,0) arc(180:360:#4 and #5); 
%%%%%%%%} 
%%%%%%%%
%%%%%%%%\def\tkzCone{\pgfutil@ifnextchar[{\tkz@cone}{\tkz@cone[]}} 
%%%%%%%%\def\tkz@cone[#1]#2#3#4{%
%%%%%%%%% #1    styles
%%%%%%%%% #2    rayon R
%%%%%%%%% #3    coeff d'aplatissement k
%%%%%%%%% #4    Hauteur du cône H   
%%%%%%%%% \pgfkeys{%
%%%%%%%%% /tkzcone/.cd
%%%%%%%%% }% 
%%%%%%%%% \pgfqkeys{/tkzcone}{#1}%     
%%%%%%%%\pgfmathsetmacro{\bb}{#2*#3}          
%%%%%%%%\pgfmathsetmacro{\yy}{\bb*\bb/#4}  
%%%%%%%%\pgfmathsetmacro{\xx}{#2*sqrt((1-\yy)/#4)} 
%%%%%%%%\fill[color=Maroon!10] (0,#4)--(-\xx,\yy)  arc(180:360:\xx cm and .5 cm); 
%%%%%%%%\ellipseThreeD[color=Maroon!30](0,0)(\xx cm,.5 cm)
%%%%%%%%\draw (0,#4)--(\xx,\yy);
%%%%%%%%\draw (0,#4)--(-\xx,\yy); 
%%%%%%%%}% 
%%%%%%%%
%%%%%%%%\def\tkzCylinder{\pgfutil@ifnextchar[{\tkz@cylinder}{\tkz@cylinder[]}} 
%%%%%%%%\def\tkz@cylinder[#1]#2#3#4{% 
%%%%%%%%\pgfmathsetmacro{\bb}{#2*#3}          
%%%%%%%%\pgfmathsetmacro{\yy}{\bb*\bb/#4}  
%%%%%%%%\pgfmathsetmacro{\xx}{#2*sqrt((1-\yy)/#4)}
%%%%%%%%  \fill[color=Maroon!10] (-\xx cm,0)--(-\xx cm,#4 cm)  
%%%%%%%%         arc(180:360:\xx cm and .5 cm)--(\xx cm,0) 
%%%%%%%%         arc(360:180:\xx cm and .5 cm);   
%%%%%%%%\ellipseThreeD[color=Maroon!30](0,0)(\xx cm,.5 cm)
%%%%%%%%\begin{scope}[yshift=#4 cm]
%%%%%%%%  \draw[fill=\cmdTKZ@ell@color,opacity=0.5,shade](0,0) ellipse (\xx cm and .5 cm) ;  
%%%%%%%%\end{scope}
%%%%%%%%\draw (\xx cm,0)--(\xx cm,#4 cm);
%%%%%%%%\draw (-\xx cm,0)--(-\xx cm,#4 cm); 
%%%%%%%%}%  
%%%%%%%%
%%%%%%%%\def\tkzTruncatedCone{\pgfutil@ifnextchar[{\tkz@TruncatedCone}{\tkz@TruncatedCone[]}} 
%%%%%%%%\def\tkz@TruncatedCone[#1]#2#3#4{%   
%%%%%%%%\pgfmathsetmacro{\bb}{#2*#3}          
%%%%%%%%\pgfmathsetmacro{\yy}{\bb*\bb/#4}  
%%%%%%%%\pgfmathsetmacro{\xx}{#2*sqrt((1-\yy)/#4)}
%%%%%%%%  \fill[color=Maroon!10] (-\xx cm,0)--(-0.5*\xx cm,#4 cm)  
%%%%%%%%    arc(180:360:0.5*\xx cm and .25 cm)--(\xx cm,0) arc(360:180:\xx cm and .5 cm);     
%%%%%%%%\ellipseThreeD[color=Maroon!30](0,0)(\xx cm,.5 cm)
%%%%%%%%\begin{scope}[yshift=#4 cm]
%%%%%%%%  \draw[fill=\cmdTKZ@ell@color,opacity=0.5,shade](0,0) ellipse (0.5*\xx cm and .25 cm);  
%%%%%%%%\end{scope}
%%%%%%%% \draw (\xx cm,0)--(0.5*\xx cm,#4 cm);
%%%%%%%% \draw (-\xx cm,0)--(-0.5*\xx cm,#4 cm); 
%%%%%%%%}%   
%%%%%%%%
%%%%%%%%\def\tkzSphere{\pgfutil@ifnextchar[{\tkz@Sphere}{\tkz@Sphere[]}} 
%%%%%%%%\def\tkz@Sphere[#1]#2#3#4{%
%%%%%%%%\pgfmathsetmacro{\bb}{#2*#3}          
%%%%%%%%\pgfmathsetmacro{\yy}{\bb*\bb/#4}  
%%%%%%%%\pgfmathsetmacro{\xx}{#2*sqrt((1-\yy)/#4)}      
%%%%%%%%\filldraw[ball color=Maroon!10] (0,0) circle[radius=\xx];
%%%%%%%%%\sellipseThreeD(0,0)(\xx cm,.25 cm)  
%%%%%%%%\begin{scope}[rotate=-90]
%%%%%%%%%\sellipseThreeD(0,0)(\xx cm,.25 cm)  
%%%%%%%%\end{scope}   
%%%%%%%%}% 
%%%%%%%%
%%%%%%%%\newcommand{\parapp}[3]{%
%%%%%%%%\fill[Maroon!10,opacity=.5] (0,0,0)-- (#1,0,0) -- (#1,#3,0)  -- (0,#3,0) --cycle;
%%%%%%%%\fill[Maroon!10,opacity=.5] (0,0,#2)-- (#1,0,#2) -- (#1,#3,#2)  -- (0,#3,#2) --cycle;
%%%%%%%%\fill[Maroon!10,opacity=.5] (0,#3,0)-- (0,#3,#2) -- (#1,#3,#2) -- (#1,#3,0)--cycle;
%%%%%%%%\fill[Maroon!10,opacity=.5] (0,0,0)-- (0,0,#2) -- (#1,0,#2) -- (#1,0,0)--cycle; 
%%%%%%%%\draw[] (0,0,#2) -- (#1,0,#2) -- (#1,#3,#2) --(0,#3,#2) --(0,0,#2)
%%%%%%%%        (#1,0,#2) -- (#1,0,0)  -- (#1,#3,0) --(0,#3,0) -- (0,#3,#2)    
%%%%%%%%        (#1,#3,#2) -- (#1,#3,0);
%%%%%%%%\draw[dashed] (0,0,0) -- (0,0,#2) (0,0,0)-- (#1,0,0) (0,0,0)-- (0,#3,0);
%%%%%%%%}

%%%%%%%%%%%%%% END , 3D Create %%%%%%%%%%%%%%%%%%%%%%

%http://tex.stackexchange.com/questions/84091/using-form-only-patterns-with-variable-possible-tikz-bug	
% Xelatex ทำ pattern ไม่ได้ แต่ pdflatex ได้ ..... งง
%\tikzset{
%    slope/.code={\edef\slope{#1}},
%    slope/.default=0.5,
%    slope
%}
%\makeatletter
%\pgfdeclarepatternformonly[\tikz@pattern@color,\slope]{slant lines}
%{\pgfpoint{-.1mm/\slope}{-.1mm}}
%{\pgfpoint{1.1mm/\slope}{1.1mm}}
%{\pgfpoint{1mm/\slope}{1mm}}
%{
%    \pgfsetlinewidth{0.4pt}
%    \pgfpathmoveto{\pgfpoint{-.1mm/\slope}{-.1mm}}
%    \pgfpathlineto{\pgfpoint{1.1mm/\slope}{1.1mm}}
%    \pgfsetstrokecolor{\tikz@pattern@color}
%    \pgfusepath{stroke}
%}
%\makeatother

% http://tex.stackexchange.com/questions/17745/diagonal-lines-in-table-cell
% ทำเส้นทะแยงหัวตาราง
%\newcolumntype{x}[1]{>{\centering\arraybackslash}p{#1}}
%\newcommand\diag[4]{%
%  \multicolumn{1}{p{#2}|}{\hskip-\tabcolsep
%  $\vcenter{
%  	\begin{tikzpicture}[baseline=0,anchor=south west,inner sep=#1]
%  		\path[use as bounding box] (0,0) rectangle (#2+2\tabcolsep,\baselineskip);
%  		\node[minimum width={#2+2\tabcolsep},minimum height=\baselineskip+\extrarowheight] (box) {};
%  		\draw (box.north west) -- (box.south east);
%  		\node[anchor=south west] at (box.south west) {#3};
%  		\node[anchor=north east] at (box.north east) {#4};
% 		\end{tikzpicture}}$
% 	\hskip-\tabcolsep}}
 	
%http://tex.stackexchange.com/questions/89745/how-to-diagonally-divide-a-table-cell-properly
%\newcommand\diag[4]{%
%  \multicolumn{1}{p{#2}|}{\hskip-\tabcolsep
%  $\vcenter{\begin{tikzpicture}[baseline=0,anchor=south west,inner sep=#1]
%  \path[use as bounding box] (0,0) rectangle (#2+2\tabcolsep,\baselineskip);
%  \node[minimum width={#2+2\tabcolsep-\pgflinewidth},
%        minimum  height=\baselineskip+\extrarowheight-\pgflinewidth] (box) {};
%  \draw[line cap=round] (box.north west) -- (box.south east);
%  \node[anchor=south west] at (box.south west) {#3};
%  \node[anchor=north east] at (box.north east) {#4};
% \end{tikzpicture}}$\hskip-\tabcolsep}}