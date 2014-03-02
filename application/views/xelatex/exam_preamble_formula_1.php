\documentclass[24pt]{article}
\usepackage{anysize}
\marginsize{5cm}{3cm}{0cm}{0cm}
\headheight 0cm
\headsep 0cm
\XeTeXlinebreaklocale "th"
\XeTeXlinebreakskip = 0pt plus 1pt %
%\oddsidemargin 0cm
%\evensidemargin 0cm
\usepackage{amsmath,amssymb,amsfonts}
%\usepackage{mathtools}
\usepackage{fontspec}
\usepackage{enumitem}
\usepackage{soul}
\usepackage{xunicode}
\usepackage{xltxtra}
\usepackage{hyperref}
\usepackage{verbatim}
\usepackage{xcolor}
\usepackage[normalem]{ulem}






\usepackage{wrapfig}
\usepackage[usenames,dvipsnames]{pstricks}
\usepackage{epsfig}
\usepackage{pst-grad} % For gradients
\usepackage{pst-plot} % For axes
\usepackage{pgf,tikz}

\usetikzlibrary{arrows}
\usetikzlibrary{calc}
\XeTeXlinebreaklocale "th"
\setmainfont[Scale=1.6]{TH Niramit AS}


\pagestyle{empty}
%แพกเกตเสริม อ้ายโต้ง


% ------------------------------------ โจทย์ + รูป + Choice environment ---------------------------------------------------------------------------------------------------
\newenvironment{ljrp} { \begin{minipage}[t]{.65\linewidth} } { \end{minipage} }
\newenvironment{ljrp2} { \begin{minipage}[t]{.5\linewidth} } { \end{minipage} }
\newenvironment{ljrp3} { \begin{minipage}[t]{.75\linewidth} } { \end{minipage} }
\newenvironment{ljrp5} { \begin{minipage}[t]{.7\linewidth} } { \end{minipage} }
\newenvironment{solve} { \begin{minipage}[t]{.9\linewidth} } { \end{minipage} }
\newenvironment{rp}[1] { \hfill \begin{adjustbox}{valign=t} \begin{minipage}[t]{.3\linewidth} \includegraphics[width=\textwidth]{#1} } { \end{minipage} \end{adjustbox} }
\newenvironment{rp2}[1] { \hfill \begin{adjustbox}{valign=t} \begin{minipage}[t]{.45\linewidth} \includegraphics[width=\textwidth]{#1} } { \end{minipage} \end{adjustbox} }
\newenvironment{rp3}[1] { \hfill \begin{adjustbox}{valign=t} \begin{minipage}[t]{.2\linewidth} \includegraphics[width=\textwidth]{#1} } { \end{minipage} \end{adjustbox} }
\newenvironment{rp4}[1] { \hfill \begin{adjustbox}{valign=t} \begin{minipage}[t]{.1\linewidth} \includegraphics[width=\textwidth]{#1} } { \end{minipage} \end{adjustbox} }
\newenvironment{rp5}[1] { \hfill \begin{adjustbox}{valign=t} \begin{minipage}[t]{.25\linewidth} \includegraphics[width=\textwidth]{#1} } { \end{minipage} \end{adjustbox} }
\newenvironment{rjlp} { \hfill \begin{minipage}[t]{.65\linewidth} } { \end{minipage} }
\newenvironment{lp}[1] { \begin{adjustbox}{valign=t} \begin{minipage}[t]{.3\linewidth} \includegraphics[width=\textwidth]{#1} } { \end{minipage} \end{adjustbox} }

