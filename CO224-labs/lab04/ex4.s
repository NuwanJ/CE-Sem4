@ ARM Assembly - exercise 4

@ Written		: Nuwan
@ LastUpdate	: 2018-11-23

	.text 	@ instruction memory
	
@ ---------------------	

	.global main
main:
	@ stack handling, will discuss later
	@ push (store) lr to the stack

	@ Write an ARM Assembly program to read string and
	@ print inverted string

    @push lr to the stack
    sub sp, sp, #4
    str lr, [sp, #0]

    @ --- Begin -------------------------------------------------------

	MOV r5, #0
	MOV r6, #0
	
wh1:
	SUB sp, sp,#1
	@MOV r4, #0		@ c <- stdin 	
	
	@ --- read char from STDIN ----------------------------------------

	LDR r0,=formats
	MOV r1, sp
	BL scanf				@ scanf(r0, r1);

	LDRB r4, [sp, #0]		@ r4 = %c 

	@ --- Compare the char with '\n'-----------------------------------
	
	CMP r4, #10
	BEQ ex1

	@ --- DEBUG: print the current character --------------------------------- 

	@SUB r5, r5, #1
	@MOV r1,r4
	@MOV r2,r5
	@LDR r0, =formatp
	@BL printf				@ printf("%c", r1); 

	@ ----------------------------------------------------------------
	ADD r5, r5, #1
	B wh1

ex1:

	ADD sp, sp, #1
	
	@MOV r1,r5
	@LDR r0, =formatt
	@BL printf

wh2:
	@ -- Print each character from sp (reverse order) ----------------

	CMP r5, r6
	BEQ ex2
	LDR r0, =formatp
	LDRB r1, [sp, r6]
	BL printf				@ printf("%c", r1); 
	
	ADD r6, r6, #1
	B wh2

ex2: 
	
	@ -- print new line char and re set the sp ----------------------

	LDR r0, =formatNL
	BL printf				@ printf("\n"); 
	
	ADD sp, sp, r5


    @----------push lr to the stack---------@
    ldr lr, [sp, #0]
    add sp, sp, #4
    mov pc, lr

	
	.data	@ data memory

formats: .asciz "%c"
formatp: .asciz "%c"
formatNL: .asciz "\n";
formatt: .asciz ">> %d -------------------\n"


