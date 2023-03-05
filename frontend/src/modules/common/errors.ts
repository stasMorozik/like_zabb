export namespace Errors {

  export interface ErrorI {
    readonly message: string
  }

  export namespace Domain {

    export class InvalidEmail implements ErrorI {
      constructor(
        readonly message: string = 'Invalid email address'
      ){}
    }

    export class InvalidPassword implements ErrorI {
      constructor(
        readonly message: string = 'Invalid password'
      ){}
    }

    export class InvalidName implements ErrorI {
      constructor(
        readonly message: string = 'Invalid name'
      ){}
    }

  }

  export namespace Infrastructure {

    export class BadRequest implements ErrorI {
      constructor(
        readonly message: string = 'Bad request'
      ){}
    }

    export class NotAuthorized implements ErrorI {
      constructor(
        readonly message: string = 'Not authorized'
      ){}
    }

    export class InternalServerError implements ErrorI {
      constructor(
        readonly message: string = 'Internal server error'
      ){}
    }

    export class NotFound implements ErrorI {
      constructor(
        readonly message: string  = 'Not found resource'
      ){}
    }

  }

}
